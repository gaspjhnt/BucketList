<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {

        $wishes = $wishRepository->findBy(["isPublished" => true], ["dateCreated" => "DESC"], 10, 0);
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, WishRepository $wishRepository): Response
    {

        $wish = $wishRepository->find($id);

        if (!$wish){
            //Permet de lancer une erreur 404
            throw $this->createNotFoundException("Oops ! Wish not found !");
        }
        return $this->render('wish/show.html.twig', [
            "wish" => $wish
        ]);
    }
}
