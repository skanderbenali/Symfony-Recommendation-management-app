<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ConseilRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ReviewType;
use App\Entity\Conseil;



class ReviewController extends AbstractController
{



    #[Route('/reviews', name: 'getReviews')]
    public function index(Request $request , ReviewRepository $repReview): Response
    {
        $reviews = $repReview->findAll();
        $numberReviews = $repReview->reviewsCount();
        return $this->render('Front/conseilsDetails.html.twig', [
            'reviews' => $reviews,
            'revCount' => $numberReviews
        ]);
    }

}
