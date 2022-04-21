<?php

namespace App\Controller;

use Exception;
use App\Entity\Todo;
use App\Form\TodoType;
use DateTimeImmutable;
use App\Form\FilterType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todolist')]
    public function todo(Request $request, TodoRepository $repo): Response
    {
        $filter = $this->createForm(FilterType::class);
        $filter->handleRequest($request);
        $todos = $repo->findAll();
        if($filter->isSubmitted() && $filter->isValid()){
            $category = $filter['categorie']->getData();
            $order = $filter['realisee']->getData()??true;
            
            $todos = $repo->findCustom($category, $order);
            
        }
           
        return $this->render('todo/todolist.html.twig', [
            'todos' => $todos,
            'filter' => $filter->createView()
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('todo/home.html.twig', [
            
        ]);
    }

    #[Route('/newtodo', name: 'todo-new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            
            $todo->setCreatedAt(new \DateTimeImmutable());
            dump($todo->getIsDone());
            if($todo->getIsDone() == true){
                $todo->setDoneAt(new \DateTimeImmutable());
            }
            
            
            $em->persist($todo);

            try{
                $em->flush($todo);
            }catch(Exception $e){
                dd($e);
                return $this->redirectToRoute('todo-new');
            }

            
        }
        
        return $this->render('todo/newtodo.html.twig', [ 
            'form' => $form->createView()
        ]);
        
        $em->flush();

        return $this->render('todo/newtodo.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/supprimer-todo/{id}', name: 'todo-delete')]
    public function delete(Todo $todo, EntityManagerInterface $em): Response
    {
       $em->remove($todo);
       $em->flush();

       return $this->redirectToRoute('todolist');
    }

    #[Route('/modifier-todo/{id}', name: 'todo-edit')]
    public function edit(Todo $todo, Request $request, EntityManagerInterface $em): Response
    {
       $form = $this->createForm(TodoType::class, $todo);
       $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $todo->setCreatedAt(new \DateTimeImmutable());
            $em->flush();
            return $this->redirectToRoute('todolist');
        }
       return $this->render('todo/edit-todo.html.twig', [
           'form' => $form->createView()
       ]);
    }

    

}
