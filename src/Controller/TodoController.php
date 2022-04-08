<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

}
