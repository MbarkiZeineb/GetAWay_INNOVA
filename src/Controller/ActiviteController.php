<?php

namespace App\Controller;
use App\Data\SearchData;
use App\Entity\Avis;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Form\SearchForm;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/", name="app_activite_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $activites = $entityManager
            ->getRepository(Activite::class)
            ->findAll();
        return $this->render('activite/login.html.twig', [
            'activites' => $activites,
        ]);

    }




    /**
     * @Route("/f", name="app_activite_indexfront", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $activites = $entityManager
            ->getRepository(Activite::class)
            ->findAll();
        return $this->render('activite/index_front.html.twig', [
            'activites' => $activites,
        ]);

    }

    /**
     * @Route("/new", name="app_activite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();
            $this->flashy->success('Activite ajouter');
            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->flashy->error("L'Ajout de l'activite à echouée");
        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refact}", name="app_activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        $this->flashy->info("Détails de l'activité");
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/actf/{refact}", name="app_activite_showfront", methods={"GET"})
     */
    public function showFront(Activite $activite): Response
    {

        return $this->render('activite/show_front.html.twig', [
            'activite' => $activite,

        ]);
    }

    /**
     * @Route("/{refact}/edit", name="app_activite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->flashy->success("Modification avec succées");
            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->flashy->error("Modification echoué");
        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refact}", name="app_activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getRefact(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }
        $this->flashy->success("Suppression avec succées");
        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }


    public function __toString()
    {
        return $this->nom;
    }
}
