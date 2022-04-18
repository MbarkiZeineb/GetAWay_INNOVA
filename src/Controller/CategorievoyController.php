<?php

namespace App\Controller;

use App\Entity\Categorievoy;
use App\Form\CategorievoyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorievoy")
 */
class CategorievoyController extends AbstractController
{
    /**
     * @Route("/", name="app_categorievoy_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorievoys = $entityManager
            ->getRepository(Categorievoy::class)
            ->findAll();

        return $this->render('categorievoy/index.html.twig', [
            'categorievoys' => $categorievoys,
        ]);
    }

    /**
     * @Route("/new", name="app_categorievoy_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorievoy = new Categorievoy();
        $form = $this->createForm(CategorievoyType::class, $categorievoy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorievoy);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorievoy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorievoy/new.html.twig', [
            'categorievoy' => $categorievoy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcat}", name="app_categorievoy_show", methods={"GET"})
     */
    public function show(Categorievoy $categorievoy): Response
    {
        return $this->render('categorievoy/show.html.twig', [
            'categorievoy' => $categorievoy,
        ]);
    }

    /**
     * @Route("/{idcat}/edit", name="app_categorievoy_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorievoy $categorievoy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorievoyType::class, $categorievoy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorievoy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorievoy/edit.html.twig', [
            'categorievoy' => $categorievoy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcat}", name="app_categorievoy_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorievoy $categorievoy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorievoy->getIdcat(), $request->request->get('_token'))) {
            $entityManager->remove($categorievoy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorievoy_index', [], Response::HTTP_SEE_OTHER);
    }
}
