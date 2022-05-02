<?php

namespace App\Controller;

use App\Entity\Voyageorganise;
use App\Form\VoyageorganiseType;
use App\Repository\ReservationRepository;
use App\Repository\VoyageorganiseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/voyageorganise")
 */
class VoyageorganiseController extends AbstractController
{
    /**
     * @Route("/", name="app_voyageorganise_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $voyageorganises = $entityManager
            ->getRepository(Voyageorganise::class)
            ->findAll();

        return $this->render('voyageorganise/index.html.twig', [
            'voyageorganises' => $voyageorganises,
        ]);
    }

    /**
     * @Route("/new", name="app_voyageorganise_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyageorganise = new Voyageorganise();
        $form = $this->createForm(VoyageorganiseType::class, $voyageorganise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyageorganise);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyageorganise/new.html.twig', [
            'voyageorganise' => $voyageorganise,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{idvoy}/edit", name="app_voyageorganise_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Voyageorganise $voyageorganise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageorganiseType::class, $voyageorganise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyageorganise/edit.html.twig', [
            'voyageorganise' => $voyageorganise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idvoy}", name="app_voyageorganise_delete", methods={"POST"})
     */
    public function delete(Request $request, Voyageorganise $voyageorganise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyageorganise->getIdvoy(), $request->request->get('_token'))) {
            $entityManager->remove($voyageorganise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
    }

    //********************mobile
    /**
     * @Route("/getallVoyage")
     */
    public function getvoyage (VoyageorganiseRepository $repository , SerializerInterface  $serializer)
    {
        $p = $repository->findAll();
        $dataJson=$serializer->serialize($p,'json',['groups'=>'voyage']);
        return new JsonResponse(json_decode($dataJson) );

    }
}
