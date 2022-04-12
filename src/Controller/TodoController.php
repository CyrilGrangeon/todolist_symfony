<?php

namespace App\Controller;

use App\Form\TodoType;
use Exception;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use DateTime;
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
            
            $todo->setIsDone(false);
            $todo->setCreatedAt(new \DateTimeImmutable());
            $todo->setDoneAt(new \DateTimeImmutable());
            
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

}
