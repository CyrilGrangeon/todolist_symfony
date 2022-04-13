<?php

namespace App\Controller;

use App\Form\TodoType;
use Exception;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todolist')]
    public function todo(TodoRepository $repo): Response
    {
        
        $todos = $repo->findAll();
        return $this->render('todo/todolist.html.twig', [
            'todos' => $todos
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
