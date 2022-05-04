<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationbackType;
use App\Form\ReclamationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
/**
     * @Route("/d/{idr}", name="deleterec", methods={"GET"})
     */
public function delete1(EntityManagerInterface $entityManager,UserRepository $userrep,$idr): Response
    {
        $rec = $entityManager
            ->getRepository(Reclamation::class)
            ->find($idr);
        $entityManager->remove($rec);
        $entityManager->flush();
        $user=$userrep->find($rec->getIdclient()->getId());
        return $this->redirectToRoute('app_reclamationfront',['idc'=>$rec->getIdclient()->getId()]);


    }
    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/new/{idc}", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$idc): Response
    {
        $user= $entityManager->getRepository(User::class)->find($idc);
        $reclamation = new Reclamation();
        $reclamation->setIdclient($user);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            return $this->redirectToRoute('app_reclamationfront',['idc'=>$reclamation->getIdclient()->getId()]);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }







    /**
     * @Route("/{idr}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{idr}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationbackType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_reclamationfront',['idc'=>$reclamation->getIdclient()->getId()]);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdr(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @return void
     * @Route("/traiter/{idr}",name="traiter")
     */
    public function  traiter($idr,EntityManagerInterface $entityManager): Response
    {
       $rec=$entityManager->getRepository(Reclamation::class)->find($idr);

       $rec->setEtat(1);
       $entityManager->flush();

       return $this->redirectToRoute('app_reclamation_index');

    }
    /**
     * @Route("/email/{idr}", name="app_email")
     */
    public function sendEmail(MailerInterface $mailer,$idr): Response
    {
        $entityManager=$this->getDoctrine()->getManager();
        $rec=$entityManager->getRepository(Reclamation::class)->find($idr);
        $user=$rec->getIdclient();
        $emaildest=$user->getEmail();
        $email = (new Email())
            ->from('omayma.djebali@esprit.tn')
            ->to($emaildest)
            ->subject('Votre reclamation est traitéé avec succés!')
            ->text('Sending emails is fun again!')
            ->html('<p>Bonjour Mme/Mr nous avons bien recu votre reclamation et nous allons la prendre en consideration!</p>');

        $mailer->send($email);
        return $this->redirectToRoute('app_reclamation_index');


    }



}
