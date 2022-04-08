<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todolist')]
    public function index(): Response
    {
        return $this->render('todo/todolist.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }
}
