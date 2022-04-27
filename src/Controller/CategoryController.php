<?php

namespace App\Controller;

use Exception;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\FileUpload;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/cree-categories', name:'category-new')]
    public function new(FileUpload $fileUploader, EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($category->getImage() === null){
                $category->setImage('default.png');
            }else{
                $imageFile = $form->get('image')->getData();
                $imageFileName = $fileUploader->upload($imageFile);
                $category->setImage($imageFileName);
            }
            $em->persist($category);

            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie créée.');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec de la création de la catégorie.');

                return $this->redirectToRoute('category-new');
            }
        }
        return $this->render("category/new.html.twig", [
            'form' => $form->createView()
            
        ]);
    }
}
