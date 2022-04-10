<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paiement")
 */
class PaiementController extends AbstractController
{
    /**
     * @Route("/", name="app_paiement_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paiements = $entityManager
            ->getRepository(Paiement::class)
            ->findAll();

        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

    /**
     * @Route("/new/{id}/{prix}", name="app_paiement_newvol", methods={"GET", "POST"})
     */
    public function newvol(Request $request, EntityManagerInterface $entityManager,$prix,$id,ReservationRepository $repR): Response
    {
        $paiement = new Paiement();
        $reservation=$repR->find($id);
        $montant=$prix *$reservation->getNbrPlace();
        $paiement->setMontant( $montant);
        $paiement->setIdReservation($reservation);
        $date1 = new \DateTime('@'.strtotime('now'));
        $paiement->setDate($date1);
        $form = $this->createForm(PaiementType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdVol()->getIdVol()));
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/new/{id}/{prix}", name="app_paiement_newvo", methods={"GET", "POST"})
     */
    public function newvo(Request $request, EntityManagerInterface $entityManager,$prix,$id,ReservationRepository $repR): Response
    {
        $paiement = new Paiement();
        $reservation=$repR->find($id);
        $montant=$prix *$reservation->getNbrPlace();
        $paiement->setMontant( $montant);
        $paiement->setIdReservation($reservation);
        $date1 = new \DateTime('@'.strtotime('now'));
        $paiement->setDate($date1);
        $form = $this->createForm(PaiementType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdVoyage()->getIdvoy()));
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/newH/{id}/{prix}/{nbrj}", name="app_paiement_newH", methods={"GET", "POST"})
     */
    public function newH(Request $request, EntityManagerInterface $entityManager,$prix,$id,$nbrj,ReservationRepository $repR): Response
    {
        $paiement = new Paiement();
        $reservation=$repR->find($id);
        $montant=$prix *$nbrj;
        $paiement->setMontant( $montant);
        $paiement->setIdReservation($reservation);
        $date1 = new \DateTime('@'.strtotime('now'));
        $paiement->setDate($date1);
        $form = $this->createForm(PaiementType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdHebergement()->getReferance()));
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_paiement_show", methods={"GET"})
     */
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }
    /**
     * @Route("/DetailsR/{id}", name="detailsR", methods={"GET"})
     */
    public function showP($id,PaiementRepository $rep): Response
    {
        $paiement=$rep->showPaiement($id);

        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_paiement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_paiement_delete", methods={"POST"})
     */
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
