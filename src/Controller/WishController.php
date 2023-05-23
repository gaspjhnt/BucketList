<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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


    #[Route('/add', name: 'add')]
    public function add(Request $request, WishRepository $wishRepository, CategoryRepository $categoryRepository): Response
    {

        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);


        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());

            $wishRepository->save($wish, true);

            $this->addFlash('success', 'Idea successfully added!');


            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }

        return $this->render('wish/add.html.twig', [
            'wishForm' => $wishForm->createView()
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
