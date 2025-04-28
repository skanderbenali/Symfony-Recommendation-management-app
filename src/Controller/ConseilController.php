<?php

namespace App\Controller;

use App\Entity\Conseil;
use App\Controller\ExcelController;
use App\Entity\Typeconseil;
use App\Entity\Produit;
use App\Form\ConseilType;
use App\Form\ReviewType;
use App\Form\ConseilFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use App\Repository\ConseilRepository;
use App\Repository\ProduitRepository;
use App\Repository\TypeConseilRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Entity\Review;
use App\Repository\ReviewRepository;



class ConseilController extends AbstractController
{

    #[Route('/base', name: 'app_base', methods: ['GET'])]
    public function GoToBase(): Response
    {
        return $this->render('base.html.twig');
    }



    #[Route('/conseils',name:'getAll')]
    public function getAll(
        EntityManagerInterface $entityManager, 
        ConseilRepository $conseilRepository, 
        ReviewRepository $reviewRepository, 
        TypeConseilRepository $typeConseilRepository, 
        PaginatorInterface $paginator, 
        Request $request, 
        ParameterBagInterface $parameterBag,
        FilterBuilderUpdaterInterface $filterBuilderUpdater
    ): Response
    {
        // Create filter form
        $filterForm = $this->createForm(ConseilFilterType::class);
        $filterForm->handleRequest($request);
        
        // Retrieve search term from request
        $searchTerm = $request->query->get('search', '');
        
        // Build query
        $queryBuilder = $conseilRepository->createQueryBuilder('c');
        
        // Apply filters from LexikFormFilterBundle if form was submitted
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }
        // Apply basic search if not using advanced filter
        elseif (!empty($searchTerm)) {
            $queryBuilder->where('c.nomConseil LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }
        
        // Get metrics
        $conseilsCount = $conseilRepository->conseilsCount();
        $reviewsCount = $reviewRepository->reviewsCount();
        $latestConseilDate = $conseilRepository->findLatestConseilDateCreation();
        
        // Get conseils by type for chart
        $conseilCountsByType = $conseilRepository->getConseilCountsByType();
        $labels = [];
        $data = [];
        
        foreach ($conseilCountsByType as $item) {
            $labels[] = $item['typeConseil'];
            $data[] = $item['count'];
        }
        
        // Create paginator
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            5
        );
        
        // Create conseil form for adding new conseil (kept as 'fc' per project convention)
        $conseil = new Conseil();
        $conseilForm = $this->createForm(ConseilType::class, $conseil);
        
        return $this->render('Back/conseil/AfficherConseils.html.twig', [
            'c' => $pagination,
            'searchTerm' => $searchTerm,
            'filter_form' => $filterForm->createView(),
            'conseilsCount' => $conseilsCount,
            'reviewsCount' => $reviewsCount,
            'number' => $conseilsCount,
            'revCount' => $reviewsCount, // Add this for backward compatibility
            'fc' => $conseilForm->createView(),
            'conseilCountsByType' => $conseilCountsByType,
            'labels' => $labels,
            'data' => $data,
            'latestConseilDate' => $latestConseilDate
        ]);
    }



    #[Route('/conseilsFront', name: 'getAllFront')]
    public function getAllFront(Request $request, ConseilRepository $repo, TypeConseilRepository $repoType, PaginatorInterface $paginator,ReviewRepository $repoReviews)
    {

        $categoryId = $request->query->getInt('category', -1);
    
        // Fetch artworks based on the selected category filter
        if ($categoryId !== -1) {
            $conseils = $repo->findBy(['idTypec' => $categoryId]);
        } else {
            // If no category is selected (-1), retrieve all artworks
            $conseils = $repo->findAll();
        }

        $averageReviewValue = $repoReviews->getAverageReviewValuesByConseil();
        $conseilsNumber = $repo->conseilsCount();
        $categories = $repoType->findAll();


        $conseilsPaginated = $paginator->paginate(
            $conseils,
            $request->query->getInt('page', 1), // Current page number, default to 1 if not provided
            3 // Number of elements per page
        );
        return $this->render('Front/conseils.html.twig', [
            'c' => $conseilsPaginated,
            'number' => $conseilsNumber,
            'categories' => $categories ,
            'averageValue' => $averageReviewValue
        ]);
    }



    #[Route('/sort-by-category-asc', name: 'sort_by_category_asc')]
    public function sortByCategoryAsc(Request $request, ConseilRepository $repo, PaginatorInterface $paginator, ReviewRepository $repoReviews): Response
    {
        // Create filter form
        $filterForm = $this->createForm(ConseilFilterType::class);
        $filterForm->handleRequest($request);
    
        // Fetch sorted conseils
        $conseil = new Conseil();
        $conseils = $repo->findAllSortedByCategoryAsc();
        $numberReviews = $repoReviews->reviewsCount();
        $latestConseilDate = $repo->findLatestConseilDateCreation();

        // Prepare data for charts
       
        $labels = [];
        $data = [];

        foreach ($conseils as $conseil) {
            $averageRating = $repoReviews->getAverageRatingByConseil($conseil->getIdConseil());
            $labels[] = $conseil->getNomConseil(); // Assuming 'name' is a property of Conseil
            $data[] = $averageRating ?? 0; // Use 0 if averageRating is null
        }
        
        // Pagination
        $conseilsPaginated = $paginator->paginate(
            $conseils,
            $request->query->getInt('page', 1),
            5
        );

        $conseilsnumber = $repo->conseilsCount();
        $conseilCountsByType = $repo->getConseilCountsByType();
        $formConseil = $this->createForm(ConseilType::class, $conseil);
        $formConseil->handleRequest($request);

        // Render the template with paginated and sorted conseils
        return $this->render('Back/conseil/AfficherConseils.html.twig', [
            'c' => $conseilsPaginated,
            'fc' => $formConseil->createView(),
            'filter_form' => $filterForm->createView(),
            'number' => $conseilsnumber,
            'conseilsCount' => $conseilsnumber,
            'reviewsCount' => $numberReviews,
            'revCount' => $numberReviews,
            'conseilCountsByType' => $conseilCountsByType,
            'latestConseilDate' => $latestConseilDate,
            'labels' => $labels,
            'data' => $data,
            'searchTerm' => '',
        ]);
    }


    #[Route('/export-to-excel', name: 'export_to_excel')]
    public function generateExcel(ExcelController $excelController): Response
    {
        $filename = $excelController->generateConseilsExcel();
    
        // Generate the full path to the Excel file
        $filePath = $this->getParameter('kernel.project_dir') . '/public/excel/' . $filename;
    
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist.');
        }
        return $this->file($filePath);
    }


    /**
     * Export recommendations to CSV with enhanced data
     */
    #[Route('/export-to-csv', name: 'export_to_csv')]
    public function exportToCsv(
        ConseilRepository $conseilRepository, 
        ReviewRepository $reviewRepository,
        Request $request
    ): Response
    {
        // Get the filter criteria from the request
        $filterForm = $this->createForm(ConseilFilterType::class);
        $filterForm->handleRequest($request);
        
        // Build query
        $queryBuilder = $conseilRepository->createQueryBuilder('c')
            ->leftJoin('c.idTypec', 't')
            ->leftJoin('c.idProduit', 'p')
            ->addSelect('t', 'p');
        
        // Apply filters if form was submitted
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterBuilderUpdater = $this->get('lexik_form_filter.query_builder_updater');
            $filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }
        
        // Get conseils with filters applied
        $conseils = $queryBuilder->getQuery()->getResult();
        
        // Create the CSV file
        $fileName = 'recommendations_export_' . date('Y-m-d_H-i-s') . '.csv';
        $csvDirectory = $this->getParameter('kernel.project_dir') . '/public/exports';
        
        // Create directory if it doesn't exist
        if (!is_dir($csvDirectory)) {
            mkdir($csvDirectory, 0777, true);
        }
        
        $filePath = $csvDirectory . '/' . $fileName;
        
        // Open CSV file for writing
        $handle = fopen($filePath, 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($handle, "\xEF\xBB\xBF");
        
        // Define CSV headers
        $headers = [
            'ID',
            'Name',
            'Description',
            'Category',
            'Related Product',
            'Creation Date',
            'Average Rating',
            'Reviews Count',
            'Video Filename',
            'Last Modified'
        ];
        
        // Write headers
        fputcsv($handle, $headers);
        
        // Write data rows
        foreach ($conseils as $conseil) {
            // Get extra metrics for each conseil
            $avgRating = $reviewRepository->getAverageRatingByConseil($conseil->getIdConseil()) ?? 'N/A';
            $reviewsCount = $reviewRepository->countReviewsByConseilId($conseil->getIdConseil()) ?? 0;
            
            // Format the date
            $dateFormatted = $conseil->getDatecreation() ? $conseil->getDatecreation()->format('Y-m-d') : 'N/A';
            
            // Get product and category names safely
            $typeConseil = $conseil->getIdTypec();
            $produit = $conseil->getIdProduit();
            $typeConseilName = $typeConseil ? $typeConseil->getNomtypec() : 'N/A';
            $produitName = $produit ? $produit->getNomProduit() : 'N/A';
            
            // Prepare row data
            $rowData = [
                $conseil->getIdConseil(),
                $conseil->getNomConseil(),
                $conseil->getDescription(),
                $typeConseilName,
                $produitName,
                $dateFormatted,
                $avgRating,
                $reviewsCount,
                $conseil->getVideo(),
                date('Y-m-d')  // Current date as last modified
            ];
            
            // Write the row
            fputcsv($handle, $rowData);
        }
        
        // Close the file
        fclose($handle);
        
        // Return the file as a download
        $response = new Response(file_get_contents($filePath));
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        
        return $response;
    }


    #[Route('/conseilsFront/{idc}', name: 'getOne')]
    public function getOne(Request $request, ConseilRepository $repo, ReviewRepository $repoReviews, TypeConseilRepository $repoType, $idc): Response
    {
        $conseil = $repo->find($idc);

        if (!$conseil) {
            throw $this->createNotFoundException('Conseil not found.');
        }

        // Retrieve reviews associated with the specified Conseil
        $reviewsByConseil = $repoReviews->findReviewsByConseilId($conseil->getIdConseil());
        $reviewsNumberByConseil = count($reviewsByConseil);

        // Calculate average rating
        $averageRating = 0;
        if ($reviewsNumberByConseil > 0) {
            $totalRating = 0;
            foreach ($reviewsByConseil as $review) {
                $totalRating += $review->getValue();
            }
            $averageRating = min(5, $totalRating / $reviewsNumberByConseil);
        }

        // Create new review instance for the form
        $review = new Review();
        $review->setIdConseil($conseil);
        $review->setDatecreation(new \DateTime());

        // Handle review form submission
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ratingValue = (int) $form->get('value')->getData();
            $review->setValue($ratingValue);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('getOne', ['idc' => $conseil->getIdConseil()]);
        }

        // Render the template with the necessary data
        return $this->render('Front/conseilsDetails.html.twig', [
            'c' => $conseil,
            'reviewsC' => $reviewsByConseil,
            'form' => $form->createView(),
            'numberReview' => $reviewsNumberByConseil,
            'averageRating' => $averageRating,
            'categories' => $repoType->findAll(),
            'conseilsCount' => $repo->conseilsCount()
        ]);
    }
    
    
    

    #[Route('/addConseil', name: 'conseil_add')]
    public function addConseil(Request $request, ConseilRepository $repo, EntityManagerInterface $entityManager, PaginatorInterface $paginator, ParameterBagInterface $parameterBag, ReviewRepository $repoReviews): Response
    {
        // Create a new Conseil entity and set creation date
        $conseil = new Conseil();
        $conseil->setDatecreation(new \DateTime());
        
        // Create the form
        $formConseil = $this->createForm(ConseilType::class, $conseil);
        $formConseil->handleRequest($request);
        
        // Create filter form for template compatibility
        $filterForm = $this->createForm(ConseilFilterType::class);
        $filterForm->handleRequest($request);

        // Get data for the template
        $conseilsnumber = $repo->conseilsCount(); 
        $query = $repo->findAll();
        $conseils = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        // Get data for charts and stats
        $labels = [];
        $data = [];
        foreach ($conseils as $c) {
            $averageRating = $repoReviews->getAverageRatingByConseil($c->getIdConseil());
            $labels[] = $c->getNomConseil();
            $data[] = $averageRating ?? 0;
        }

        $numberReviews = $repoReviews->reviewsCount();
        $latestConseilDate = $repo->findLatestConseilDateCreation();
        $conseilCountsByType = $repo->getConseilCountsByType();

        // Handle form submission
        if ($formConseil->isSubmitted() && $formConseil->isValid()) {
            // Handle video file upload - this field is not mapped to the entity
            /** @var UploadedFile $videoFile */
            $videoFile = $formConseil->get('video')->getData();
            
            if ($videoFile) {
                // Define upload directory - using absolute path
                $uploadDir = 'uploads/conseil/';
                $publicDir = $this->getParameter('kernel.project_dir') . '/public/';
                $targetDir = $publicDir . $uploadDir;
                
                // Create directory if it doesn't exist
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Generate unique filename
                $newFilename = uniqid() . '.' . $videoFile->guessExtension();
                
                try {
                    // Move file to directory
                    $videoFile->move($targetDir, $newFilename);
                    
                    // Set video filename - store only the filename
                    $conseil->setVideo($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error uploading video file: ' . $e->getMessage());
                    return $this->redirectToRoute('getAll');
                }
            } else {
                // If no video provided, set a placeholder
                $conseil->setVideo('placeholder.mp4');
            }

            try {
                // Save the recommendation to the database
                $entityManager->persist($conseil);
                $entityManager->flush();
                
                $this->addFlash('success', 'Recommendation created successfully');
                return $this->redirectToRoute('getAll');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating recommendation: ' . $e->getMessage());
            }
        }

        // Render the template
        return $this->render('Back/conseil/AfficherConseils.html.twig', [
            'fc' => $formConseil->createView(),
            'c' => $conseils,
            'number' => $conseilsnumber,
            'conseilsCount' => $conseilsnumber,
            'revCount' => $numberReviews,
            'reviewsCount' => $numberReviews,
            'latestConseilDate' => $latestConseilDate,
            'conseilCountsByType' => $conseilCountsByType,
            'filter_form' => $filterForm->createView(),
            'labels' => $labels,
            'data' => $data,
            'searchTerm' => '',
        ]); 
    }
    

    #[Route('/conseil/new', name: 'conseil_new')]
    public function newConseil(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Conseil entity and set creation date
        $conseil = new Conseil();
        $conseil->setDatecreation(new \DateTime());
        
        // Create the form
        $formConseil = $this->createForm(ConseilType::class, $conseil);
        $formConseil->handleRequest($request);
        
        // Handle form submission
        if ($formConseil->isSubmitted() && $formConseil->isValid()) {
            // Handle video file upload - this field is not mapped to the entity
            /** @var UploadedFile $videoFile */
            $videoFile = $formConseil->get('video')->getData();
            
            if ($videoFile) {
                // Define upload directory - using absolute path
                $uploadDir = 'uploads/conseil/';
                $publicDir = $this->getParameter('kernel.project_dir') . '/public/';
                $targetDir = $publicDir . $uploadDir;
                
                // Create directory if it doesn't exist
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Generate unique filename
                $newFilename = uniqid() . '.' . $videoFile->guessExtension();
                
                try {
                    // Move file to directory
                    $videoFile->move($targetDir, $newFilename);
                    
                    // Set video filename - store only the filename
                    $conseil->setVideo($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error uploading video file: ' . $e->getMessage());
                    return $this->redirectToRoute('getAll');
                }
            } else {
                // If no video provided, set a placeholder
                $conseil->setVideo('placeholder.mp4');
            }

            try {
                // Save the recommendation to the database
                $entityManager->persist($conseil);
                $entityManager->flush();
                
                $this->addFlash('success', 'Recommendation created successfully');
                return $this->redirectToRoute('getAll');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating recommendation: ' . $e->getMessage());
            }
        }

        // Render the template
        return $this->render('Back/conseil/add_conseil.html.twig', [
            'fc' => $formConseil->createView()
        ]); 
    }
    

    #[Route('/update/{idc}', name: 'conseil_update')]
    public function updateConseil($idc,ConseilRepository $repo,Request $req,EntityManagerInterface $manager,ParameterBagInterface $parameterBag){
        $conseil =$repo->find($idc);
        //$conseil->setDateCreation(new \DateTime());
        $form = $this->createForm(ConseilType::class,$conseil);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            if ($videoFile = $form['video']->getData()) {
                $videoDir = $parameterBag->get('video_dir');    
                $Filename = uniqid().'.'.$videoFile->guessExtension();
                $videoFile->move($videoDir , $Filename);

                $conseil->setVideo($Filename);
            }
        $manager->flush();
        return $this->redirectToRoute('getAll');
        }
        return $this->render('Back/conseil/Modifier.html.twig',[
            'fc'=>$form->createView()
        ]);
    }

    #[Route('/conseil/delete/{id}', name: 'conseil_delete')]
    public function deleteConseil(ManagerRegistry $manager,ConseilRepository $repo,$id){
        $conseil = $repo->find($id);
        if ($conseil){     
        $manager->getManager()->remove($conseil);
        $manager->getManager()->flush();
        return $this->redirectToRoute('getAll');
    
        }
         else return new Response("There is no conseil with this ID!");
    }

    

}
