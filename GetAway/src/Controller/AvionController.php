<?php

namespace App\Controller;

use App\Entity\Avion;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\AvionType;
use App\Repository\AvionRepository;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avion")
 */
class AvionController extends AbstractController
{
    /**
     * @Route("/", name="app_avion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avions = $entityManager
            ->getRepository(Avion::class)
            ->findAll();

        return $this->render('avion/index.html.twig', [
            'avions' => $avions,
        ]);
    }


    /**
     * @Route("/new/{ida}", name="app_avion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$ida): Response
    { $user= $entityManager->getRepository(User::class)->find($ida);

        $avion = new Avion();
        $avion->setIdAgence($user);
        $form = $this->createForm(AvionType::class, $avion);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $avion->setType($user->getNomagence());

            $entityManager->persist($avion);
            $entityManager->flush();
            $this->addFlash('info', 'Avion ajouté avec succès');

            return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
        }


        return $this->render('avion/new.html.twig', [
            'avion' => $avion,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{idAvion}", name="app_avion_show", methods={"GET"})
     */
    public function show(Avion $avion): Response
    {
        return $this->render('avion/show.html.twig', [
            'avion' => $avion,
        ]);
    }

    /**
     * @Route("/{idAvion}/edit", name="app_avion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Avion $avion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvionType::class, $avion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Avion modifié avec succès');
            return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
        }

        return $this->render('avion/edit.html.twig', [
            'avion' => $avion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idAvion}", name="app_avion_delete", methods={"POST"})
     */
    public function delete(Request $request, Avion $avion,VolRepository $volRepository,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avion->getIdAvion(), $request->request->get('_token'))) {

            $entityManager->remove($avion);
            $entityManager->flush();
            $this->addFlash('info', 'Avion et ses listes des vols sont supprimés avec succès');
        }

        return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
    }

    /**
     *@Route("/trieravion/{id}", name="sortedAvion")
     */
    public function TriA(AvionRepository $rep,$id)
    {

             $avion=$rep->TriA();

        return $this->render('avion/index.html.twig', [
        'avions' => $avion
        ]);

    }

    /**
     * @Route("/affichevol/{ida}", name="app_vol", methods={"GET"})
     */
    public function listvolByid(UserRepository $userRepository,VolRepository $volRepository, AvionRepository $avionRepository,$ida): Response
    {
        $user=$avionRepository->find($ida);
        $vol=$volRepository->listByidv($user->getIdAvion());
        return $this->render('vol/index.html.twig', [
            'user'=>$user,
            'vols' => $vol,
        ]);
    }

}
