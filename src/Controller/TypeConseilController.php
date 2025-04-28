<?php

namespace App\Controller;

use App\Entity\Typeconseil;
use App\Repository\TypeConseilRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\TypeConseilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TypeConseilController extends AbstractController
{
    #[Route('/base', name: 'app_base', methods: ['GET'])]
    public function GoToBase(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/typeC',name:'getAllType')]
    public function getAllType(Request $request,TypeConseilRepository $repo){
        $typeC = new Typeconseil();
        $typeconseils = $repo->findAll();
        $form = $this->createForm(TypeConseilType::class, $typeC);
        $form->handleRequest($request);    
        return $this->render('Back/type_conseil/AfficherTypeConseils.html.twig',[
            'c'=>$typeconseils,
            'f' => $form->createView(),
        ]);
    }

    #[Route('/addTypeConseil', name: 'typeconseil_add')]
    public function addTypeConseil(Request $request, TypeConseilRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $typec = new Typeconseil();
        //$typec->setDateCreation(new \DateTime());
    
        $form = $this->createForm(TypeConseilType::class, $typec);
        $form->handleRequest($request);    
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($typec);
            $entityManager->flush();
    
            return $this->redirectToRoute('getAllType');
        }
    
        return $this->render('Back/type_conseil/AfficherTypeConseils.html.twig', [
            'f' => $form->createView(),
            'c' => $repo->findAll(),
        ]); 
    }

    #[Route('/updateType/{id}', name: 'Typeconseil_update')]
    public function updateConseil($id,TypeConseilRepository $repo,Request $req,EntityManagerInterface $manager){
        $typec =$repo->find($id);
        //$conseil->setDateCreation(new \DateTime());
        $form = $this->createForm(TypeConseilType::class,$typec);
        $form->handleRequest($req);
        if($form->isSubmitted()){
        $manager->flush();
        return $this->redirectToRoute('getAllType');
        }
        return $this->render('Back/type_conseil/ModifierType.html.twig',[
            'f'=>$form->createView(),
        ]);
    }

    #[Route('/typeconseil/delete/{id}', name: 'typeconseil_delete')]
    public function deleteTypeConseil(ManagerRegistry $manager,TypeConseilRepository $repo,$id){
        $typec = $repo->find($id);
        if ($typec){     
        $manager->getManager()->remove($typec);
        $manager->getManager()->flush();
        return $this->redirectToRoute('getAllType');
    
        }
         else return new Response("There is no conseil with this ID!");
    }

    
}
