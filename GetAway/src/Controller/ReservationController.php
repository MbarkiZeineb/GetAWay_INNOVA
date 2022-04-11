<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationHbergementType;
use App\Form\ReservationType;
use App\Form\ReservationVoyType;
use App\Repository\ActiviteRepository;
use App\Repository\HebergementRepository;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageorganiseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_reservation_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/AfficherClient", name="AfficherClient")
     */
    public function AfficherClient(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/AfficherFront.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    /**
     * @Route("/listr", name="app_reservation_imp")
     */
    public function imprimer(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager->getRepository(Reservation::class)
            ->findAll();


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation/listR.html.twig', [
            'reservations' => $reservations,

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);


    }



    /**
     * @Route("/addVO/{id}", name="add_vo", methods={"GET", "POST"})
     */
    public function addVoyage(Request $request, EntityManagerInterface $entityManager,$id,VoyageorganiseRepository $repv): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationVoyType::class, $reservation);
        $form->handleRequest($request);
          $voyage=$repv->find($id);
        $dated= new \DateTime($voyage->getDatedepart());
        $datef= new \DateTime($voyage->getDatearrive());
          $reservation->setDateDebut($dated);
        $reservation->setDateFin($datef);
        $reservation->setIdVoyage($voyage);
        $reservation->setType("voyageOrganise");
        $reservation->setEtat("En attente");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);

        if ($form->isSubmitted() && $form->isValid()){
            if($voyage->getNbrplace() >= $reservation->getNbrPlace())
            {

                $entityManager->persist($reservation);
                $voyage->setNbrplace($voyage->getNbrplace() -  $reservation->getNbrPlace());
                $entityManager->flush();
                $entityManager->refresh($reservation);
                return $this->redirectToRoute('app_paiement_newvo', array('id' => $reservation->getId(),'prix'=>$voyage->getPrix()));
            }
            else
            {
                $this->addFlash('warning','nombre de place non disponible  ');
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }

        return $this->render('reservation/addVo.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/addVOL/{id}", name="add_vol", methods={"GET", "POST"})
     */
    public function addVol(Request $request, EntityManagerInterface $entityManager,$id,VolRepository $repv): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationVoyType::class, $reservation);
        $form->handleRequest($request);
        $vol=$repv->find($id);
        $reservation->setDateDebut($vol->getDateDepart());
        $reservation->setDateFin($vol->getDateArrivee());
        $reservation->setIdVol($vol);
        $reservation->setType("Vol");
        $reservation->setEtat("Approuve");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);
        if ($form->isSubmitted() && $form->isValid()  ) {
            if($vol->getNbrPlacedispo() > $reservation->getNbrPlace())
            {
                $entityManager->persist($reservation);
                $vol->setNbrPlacedispo($vol->getNbrPlacedispo() -  $reservation->getNbrPlace());
                $entityManager->flush();
                $entityManager->refresh($reservation);

                return $this->redirectToRoute('app_paiement_newvol', array('id' => $reservation->getId(),'prix'=>$vol->getPrix()));
            }
            else
            {
                $this->addFlash('warning','nombre de place non disponible  ');
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }

        return $this->render('reservation/addVol.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addH/{id}", name="add_H", methods={"GET", "POST"})
     */
    public function addH(Request $request, EntityManagerInterface $entityManager,$id,ReservationRepository $repv,HebergementRepository $reph ): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationHbergementType::class,$reservation);
        $form->handleRequest($request);
        $date = new \DateTime('@'.strtotime('now'));
        $heb=$reph->find($id);
        $reservation->setNbrPlace(0)
            ->setType("Hebergement")
            ->setEtat("Approuve")
            ->setDateReservation($date)
            ->setIdHebergement($reph->find($heb));
        $check1=$repv->check1($id,$reservation->getDateDebut());
        $check2=$repv->check2($id,$reservation->getDateDebut());
        $check3=$repv->check3($id,$reservation->getDateDebut(),$reservation->getDateFin());
        $check4=$repv->check4($id,$reservation->getDateDebut(),$reservation->getDateFin());
        $check5=$repv->check4($id,$reservation->getDateDebut(),$reservation->getDateFin());

        if($form->isSubmitted() && $form->isValid())
        {
            if(empty($check1)&&empty($check2)&&empty($check3)&&empty($check4)&&empty($check5)&&$reservation->getDateDebut() >= $heb->getDateStart()&&$reservation->getDateFin() <= $heb->getDateEnd() )
            {
                $entityManager->persist($reservation);
                $entityManager->flush();
                $entityManager->refresh($reservation);
                $intvl = $reservation->getDateDebut()->diff($reservation->getDateFin());

                return $this->redirectToRoute('app_paiement_newH', array('id' => $reservation->getId(),'prix'=>$heb->getPrix(),'nbrj'=>$intvl->d));
            }
            else
            {
                $this->addFlash('warning',' les dates selectionees non disponible   ');
                return $this->render('reservation/AddHebergement.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);

            }



        }
        return $this->render('reservation/AddHebergement.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, PaiementRepository $rpp,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/addAct/{id}", name="add_Act", methods={"GET", "POST"})
     */
    public function addAct(Request $request, EntityManagerInterface $entityManager,$id,ActiviteRepository  $repv): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationVoyType::class,$reservation);
        $form->handleRequest($request);
        $act=$repv->find($id);
        $dated= new \DateTime($act->getDate());
        $datef= new \DateTime($act->getDate());
        $reservation->setDateDebut($dated);
        $reservation->setDateFin($datef);
        $reservation->setIdActivite($act);
        $reservation->setType("Activite");
        $reservation->setEtat("Approuve");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);
        dump("hello");
        if ($form->isSubmitted() && $form->isValid()){

            if($act->getNbrplace() >=  $reservation->getNbrPlace())
            {
                 dump("hello");
                $entityManager->persist($reservation);
                $act->setNbrplace($act->getNbrplace() -  $reservation->getNbrPlace());
                $entityManager->flush();
                $entityManager->refresh($reservation);
                return $this->redirectToRoute('app_paiement_newAct', array('id' => $reservation->getId(),'prix'=>$act->getPrix()));
            }
            else
            {
                $this->addFlash('warning','nombre de place non disponible  ');
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }

        return $this->render('reservation/addVo.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
}
