<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/email/{idc}", name="app_email")
     */
    public function sendEmail(MailerInterface $mailer,$idc): Response
    {
        $entityManager=$this->getDoctrine()->getManager();
        $user=$entityManager->getRepository(User::class)->find($idc);
        $emaildest=$user->getEmail();
        $email = (new Email())
            ->from('omayma.djebali@esprit.tn')
            ->to('$emaildest')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
    }
}
