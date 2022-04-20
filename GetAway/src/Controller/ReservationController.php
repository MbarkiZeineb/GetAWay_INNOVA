<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ModifierReservationType;
use App\Form\ReservationHbergementType;
use App\Form\ReservationType;
use App\Form\ReservationVoyType;
use App\Repository\ActiviteRepository;
use App\Repository\HebergementRepository;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
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
     * @Route("/calendar", name="booking_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('reservation/calendar.html.twig');
    }

    /**
     * @Route("/AfficherClient/{id}", name="AfficherClient")
     */
    public function AfficherClient(EntityManagerInterface $entityManager, $id,ReservationRepository $rep): Response
    {
        dump($id);
        $reservations=$rep->listReservationByidc($id);
                dump("aaaaaaaaaaaaaaaaaaa");
        return $this->render('reservation/AfficherFront.html.twig', [
            'reservations' => $reservations,
        ]);
    }






    /**
     * @Route("/addVO/{id}", name="add_vo", methods={"GET", "POST"})
     */
    public function addVoyage(Request $request,EntityManagerInterface $entityManager,$id,VoyageorganiseRepository $repv,UserRepository  $repU): Response
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
        $reservation->setEtat("Approuve");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);

         dump($this->getUser());
           if($this->getUser()!=null)
           {  if ($form->isSubmitted() && $form->isValid()){
            if($voyage->getNbrplace() >= $reservation->getNbrPlace())
            {
                $user =$repU->find($this->getUser()->getUsername());
                $reservation->setIdClient($user);
                $entityManager->persist($reservation);
                $voyage->setNbrplace($voyage->getNbrplace() -  $reservation->getNbrPlace());

                $entityManager->flush();
                $entityManager->refresh($reservation);

                return $this->redirectToRoute('app_paiement_newvo', array('id' => $reservation->getId(),'prix'=>$voyage->getPrix()));
            }
            else
            {  $message=" nombre de place non disponible il reste ".''.$voyage->getNbrplace().''." palces seulement ";
                $this->addFlash('warning',$message);
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }}
           else
           {
               return $this->render('user/login.html.twig');
           }

        return $this->render('reservation/addVo.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/addVOL/{id}", name="add_vol", methods={"GET", "POST"})
     */
    public function addVol(Request $request, EntityManagerInterface $entityManager,$id,VolRepository $repv,   UserRepository  $repU): Response
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
        if($this->getUser()!=null)
        {          $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);
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
            { $message=" nombre de place non disponible il reste ".''.$vol->getNbrPlacedispo().''." palces seulement ";
                $this->addFlash('warning',$message);
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }}
        else{
            return $this->render('user/login.html.twig');
        }

        return $this->render('reservation/addVol.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addH/{id}", name="add_H", methods={"GET", "POST"})
     */
    public function addH(Request $request,   UserRepository  $repU,EntityManagerInterface $entityManager,$id,ReservationRepository $repv,HebergementRepository $reph ): Response
    {
        $reservation = new Reservation();
        $heb=$reph->find($id);
        $form = $this->createForm(ReservationHbergementType::class,$reservation);
        $form->handleRequest($request);
        $date = new \DateTime('@'.strtotime('now'));

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
        if($this->getUser()!=null)
        {          $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);
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

                $this->addFlash('warning',' les dates selectionees ne sont pas  disponible   ');
                return $this->render('reservation/AddHebergement.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);

            }}
        else
        {
            return $this->render('user/login.html.twig');
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
     * @Route("/edit/{id}", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation,$id,  EntityManagerInterface $entityManager,VolRepository $repvol,VoyageorganiseRepository $repvo,ActiviteRepository $repa): Response
    {

        $form = $this->createForm(ModifierReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           if($reservation->getEtat()=="Annulee")
           {
               if($reservation->getType()=="Vol")
               {
                   $vol= $repvol->find($reservation->getIdVol()->getIdVol());
                   $vol->setNbrPlacedispo($vol->getNbrPlacedispo()+$reservation->getNbrPlace());
                   $entityManager->flush();
               }
               if($reservation->getType()=="voyageOrganise")
               {
                   $voy= $repvo->find($reservation->getIdVoyage()->getIdvoy());
                   $voy->setNbrplace($voy->getNbrplace()+$reservation->getNbrPlace());
                   $entityManager->flush();
               }
               if($reservation->getType()=="Activite")
               {
                   $act= $repa->find($reservation->getIdActivite()->getRefact());
                   $act->setNbrplace($act->getNbrplace()+$reservation->getNbrPlace());
                   $entityManager->flush();
               }
               else
               {
                   $entityManager->flush();
               }


               return $this->redirectToRoute('AfficherClient');

           }



            return $this->redirectToRoute('AfficherClient');
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
    public function addAct(Request $request,UserRepository $repU,EntityManagerInterface $entityManager,$id,ActiviteRepository  $repv): Response
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

        if($this->getUser()!=null)
        {          $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);
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
                $message=" nombre de place non disponible il reste ".''.$act->getNbrplace().''." palces seulement ";
                $this->addFlash('warning',$message);
                return $this->render('reservation/addVo.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }}
        else
        {return $this->render('user/login.html.twig');
        }

        return $this->render('reservation/addVo.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/{idvol}/{idact}/{idvoy}/{quantite}", name="reservation_group", methods={"GET", "POST"})
     */
    public function addGroup(Request $request, UserRepository $repU,EntityManagerInterface $entityManager,$idvoy,$idvol,$idact,$quantite,VolRepository $repvol,VoyageorganiseRepository $rep,ActiviteRepository $repa): Response
    {
                 $voyage=$rep->find($idvoy);
                 $vol=$repvol->find($idvol);
                 $act=$repa->find($idact);
                $reservation = new Reservation();
        if($this->getUser()!=null) {
            $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);
            if ($voyage->getNbrplace() > $quantite && $vol->getNbrPlacedispo() > $quantite && $act->getNbrplace() > $quantite) {
                $reservation->setDateDebut($vol->getDateDepart());
                $reservation->setDateFin($vol->getDateArrivee());
                $reservation->setType("Activite/voyage/vol");
                $reservation->setEtat("Approuve");
                $date = new \DateTime('@' . strtotime('now'));
                $reservation->setDateReservation($date);
                $reservation->setIdVol($vol);
                $reservation->setIdVoyage($voyage);
                $reservation->setIdActivite($act);
                $reservation->setNbrPlace($quantite);
                $prixT = $voyage->getPrix() + $vol->getPrix() + $act->getPrix();
                $entityManager->persist($reservation);
                $act->setNbrplace($act->getNbrplace() -  $reservation->getNbrPlace());
                $voyage->setNbrplace($voyage->getNbrplace() -  $reservation->getNbrPlace());
                $vol->setNbrPlacedispo($vol->getNbrPlacedispo() -  $reservation->getNbrPlace());
                $entityManager->flush();
                $entityManager->refresh($reservation);
                return $this->redirectToRoute('app_paiement_newgroup', array('id' => $reservation->getId(),'prix'=>$prixT));

            }
            else
            {

                return $this->redirectToRoute('cart_panier');
            }

        }


    }



}
