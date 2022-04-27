<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class MailerController extends AbstractController
{
    #[Route('/mailer/{id}', name: 'app_mailer')]
    public function mailer(MailerInterface $mailer, Security $security): Response
    {
        $user = $security->getUser();
        $userMail = $user->getUserIdentifier();
        $email = (new TemplatedEmail())
    ->from('fabien@example.com')
    ->to('ryan@example.com')
    ->subject('Thanks for signing up!')

    // path of the Twig template to render
    ->htmlTemplate('mailer/index.html.twig')

    // pass variables (name => value) to the template
    ->context([
       'userEmail' => $userMail
    ])
;

        $mailer->send($email);
        return $this->redirectToRoute('homepage');
        
    }
}
