<?php

namespace App\Controller;

use Exception;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/liste-des-categories', name:'category-list')]
    public function list(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }
    #[Route('/delete-categories', name:'category-delete')]
    public function delete(EntityManagerInterface $em, Category $cat): Response
    {
        $em->remove($cat);
        try{
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la suppression de la catégorie.');
        }

        return $this->redirectToRoute("category-list");
    }
}
