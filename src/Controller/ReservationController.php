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
use App\Repository\VoyOrgRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;



/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{

    /**
     * @Route("/backafficher", name="app_reservation_index",methods={"GET", "POST"})
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
     * @Route("/calendar", name="booking_calendar",methods={"GET", "POST"})
     */
    public function calendar(): Response
    {
        dump("hello");
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
    public function addVoyage(Request $request,EntityManagerInterface $entityManager,$id,VoyOrgRepository $repv,UserRepository  $repU): Response
    {

        $reservation = new Reservation();
        $reservation->setNbrPlace($request->request->get('qtevoy'));
        $voyage=$repv->find($id);
        //$dated= new \DateTime($voyage->getDatedepart());
        //$datef= new \DateTime($voyage->getDatearrive());
        $reservation->setDateDebut($voyage->getDatedepart());
        $reservation->setDateFin($voyage->getDatearrive());
        $reservation->setIdVoyage($voyage);
        $reservation->setType("voyageOrganise");
        $reservation->setEtat("Approuve");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);
        if($this->getUser()!=null)
        {
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
                $this->addFlash('warningnb',$message);
                return $this->redirectToRoute('cart_panier');
            }

        }

        return $this->render('user/login.html.twig');


    }


    /**
     * @Route("/addVOL/{id}", name="add_vol", methods={"GET", "POST"})
     */
    public function addVol(Request $request, EntityManagerInterface $entityManager,$id,VolRepository $repv,   UserRepository  $repU): Response
    {
        $vol=$repv->find($id);
        $reservation = new Reservation();
        $reservation->setNbrPlace($request->request->get('qtevol'));
        $prix=$vol->getPrix()*$request->request->get('qtevol');
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

            if($vol->getNbrPlacedispo() > $reservation->getNbrPlace())
            {
                $entityManager->persist($reservation);
                $vol->setNbrPlacedispo($vol->getNbrPlacedispo() -  $reservation->getNbrPlace());
                $entityManager->flush();
                $entityManager->refresh($reservation);

                return $this->redirectToRoute('app_paiement_newvol', array('id' => $reservation->getId(),'prix'=>$prix));
            }
            else
            { $message=" nombre de place non disponible il reste ".''.$vol->getNbrPlacedispo().''." palces seulement ";
                $this->addFlash('warningnb',$message);
                return $this->redirectToRoute('cart_panier');

            }
        }
        else
            return $this->render('user/login.html.twig');


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
        $check2=$repv->check2($id,$reservation->getDateFin());
        $check3=$repv->check3($id,$reservation->getDateDebut(),$reservation->getDateFin());
        $check4=$repv->check4($id,$reservation->getDateDebut(),$reservation->getDateFin());
        $check5=$repv->check5($id,$reservation->getDateDebut(),$reservation->getDateFin());
        dump($this->getUser());
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
                    dump("rrrrrrrrrrrr00");
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

        }
        else
        {
            return $this->render('user/login.html.twig');
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
    public function edit(Request $request, Reservation $reservation,$id,  EntityManagerInterface $entityManager,VolRepository $repvol,VoyOrgRepository $repvo,ActiviteRepository $repa): Response
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


                return $this->redirectToRoute('AfficherClient',array('id'=>$reservation->getIdClient()->getId()));

            }



            return $this->redirectToRoute('AfficherClient');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deleteR/{id}", name="app_reservation_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Reservation $reservation, PaiementRepository $rpp,EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($reservation);
        $entityManager->flush();


        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/addAct/{id}", name="add_Act", methods={"GET", "POST"})
     */
    public function addAct(Request $request,UserRepository $repU,EntityManagerInterface $entityManager,$id,ActiviteRepository  $repv): Response
    {
        $reservation = new Reservation();
        $act=$repv->find($id);
        dump("aaa");
        $reservation->setNbrPlace($request->request->get('qteact'));
        //$dated= new \Date($act->getDate());
        //$datef= new \Date($act->getDate());
        $reservation->setDateDebut(($act->getDate()));
        $reservation->setDateFin(($act->getDate()));
        $reservation->setIdActivite($act);
        $reservation->setType("Activite");
        $reservation->setEtat("Approuve");
        $date = new \DateTime('@'.strtotime('now'));
        $reservation->setDateReservation($date);
        if($this->getUser()!=null)
        {          $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);


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
                $this->addFlash('warningnb',$message);
                return $this->redirectToRoute('cart_panier');


            }

        }

        return $this->render('user/login.html.twig');



    }

    /**
     * @Route("/group/{idvol}/{idact}/{idvoy}/{quantite}", name="reservation_all", methods={"GET", "POST"})
     */
    public function addGroup(Request $request, UserRepository $repU,EntityManagerInterface $entityManager,$idvoy,$idvol,$idact,$quantite,VolRepository $repvol,VoyOrgRepository $rep,ActiviteRepository $repa): Response
    {
        $voyage=$rep->find($idvoy);
        $vol=$repvol->find($idvol);
        $act=$repa->find($idact);
        $reservation = new Reservation();
        if($this->getUser()!=null) {
            $user =$repU->find($this->getUser()->getUsername());
            $reservation->setIdClient($user);
            if ($voyage->getNbrplace() >= $quantite && $vol->getNbrPlacedispo() >= $quantite && $act->getNbrplace() >= $quantite) {
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
                $this->addFlash('warningnb',"nombre de place non disponible ");
                return $this->redirectToRoute('cart_panier');
            }

        }
        return $this->redirectToRoute('cart_panier');

    }


    /**
     * @Route ("/statistiqueP/", name="app_reservation_show", methods={"GET", "POST"})
     */
    public function statP()
    {

        return $this->render('reservation/statistique.html.twig');
    }

    /**
     * @Route ("/statistiqueP22/", name="app_vv", methods={"GET", "POST"})
     */
    public function statmmm(ReservationRepository  $rep)
    {
        $reservation = $rep->stat();
        $type = [];
        $nbre= [];
        foreach($reservation as $re){

            $type [] = $re['type'];
            $nbre[] = $re['count'];
        }
        dump( $type);
        dump( $nbre);
        return $this->render('reservation/statistique.html.twig',[
            'type'=> json_encode($type),'nbre'=>json_encode($nbre),
        ]);
    }


    /**
     * @Route ("/QrcodeR/{id}", name="QrcodeReservation", methods={"GET", "POST"})
     */
    public function QrCode($id,ReservationRepository $rep,PaiementRepository $repp)
    {    $reservation=$rep->find($id);
        $paiment=$repp->findBy(['idReservation'=>$reservation]);
        $data= $reservation->show().' Montant total '.$paiment[0]->getMontant();
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText($reservation->getType())
            ->build();

        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());
        echo $result->getString();

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        return $this->render("reservation/qrcode.html.twig", ['data'=>$dataUri]);

    }
    //***************************************Mobile*******

    /**
     * @Route ("/addReservationVoy" ,  name="addrvoymobile")
     */
    public function addvoymobile(Request $request , NormalizerInterface $normalizer , EntityManagerInterface  $em,VoyOrgRepository $repvoy,UserRepository $repuser){

        $voy= $repvoy->find($request->get("idh"));
        $voy->setNbrplace($voy->getNbrplace()-$request->get("nbplace"));
        $client=$repuser->find($request->get("idclient"));
        $reservation = new Reservation();
        $reservation->setNbrPlace($request->get("nbplace"));
        $reservation->setIdClient($client);
        $reservation->setIdVoyage($voy);
        $date  = new \DateTime('@' . strtotime('now'));
        $reservation->setDateReservation( $date);
        $dated= new \DateTime($voy->getDatedepart());
        $datef= new \DateTime($voy->getDatearrive());
        $reservation->setDateDebut($dated);
        $reservation->setDateFin($datef);
        $reservation->setType("voyageOrganise");
        $reservation->setEtat("Approuve");
        $em->persist($reservation);
        $em->flush();
        $em->refresh($reservation);
        $dataJson=$normalizer->normalize($reservation->getId(),'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }
    /**
     * @Route("/GetReservation", name="GetReservation")
     */
    public function getReservation (ReservationRepository $repository , SerializerInterface  $serializer,Request $request)
    {

        $p=$repository->listReservationByidc($request->get("id"));
        $dataJson=$serializer->serialize($p,'json',['groups'=>'reservation']);
        // dd($dataJson);
        return new JsonResponse(json_decode($dataJson) );
    }

    /**
     * @Route ("/verifierdateH" ,  name="verifierheb")
     */
    public function verfierheb(Request $request , NormalizerInterface $normalizer , EntityManagerInterface  $em,ReservationRepository $repv,HebergementRepository $reph){

        $heb= $reph->find($request->get("idh"));

        $dated= new \DateTime($request->get("dated"));
        $datef= new \DateTime($request->get("datef"));
        $check1=$repv->check1($heb->getReferance(),$dated);
        $check2=$repv->check2($heb->getReferance(),$datef);
        $check3=$repv->check3($heb->getReferance(),$dated,$datef);
        $check4=$repv->check4($heb->getReferance(),$dated,$datef);
        $check5=$repv->check5($heb->getReferance(),$dated,$datef);
        //dd($check1,$check2,$check4,$check5,$dated >= $heb->getDateStart(),$datef <= $heb->getDateEnd(),$dated,$datef);
        $test=empty($check1)&&empty($check2)&&empty($check3)&&empty($check4)&&empty($check5)&&$dated >= $heb->getDateStart()&&$datef <= $heb->getDateEnd();
        $dataJson=$normalizer->normalize($test,'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }
    /**
     * @Route ("/addReservationHeb" ,  name="addrhebbmobile")
     */
    public function addhebmobile(Request $request , NormalizerInterface $normalizer , EntityManagerInterface  $em,HebergementRepository $reph,UserRepository $repuser){


        $heb= $reph->find($request->get("idh"));
        $dated= new \DateTime($request->get("dated"));
        $datef= new \DateTime($request->get("datef"));
        $client=$repuser->find($request->get("idclient"));
        $reservation = new Reservation();
        $reservation->setIdClient($client);
        $date  = new \DateTime('@' . strtotime('now'));
        $reservation->setDateDebut($dated);
        $reservation->setDateFin($datef);
        $reservation->setNbrPlace(0)
            ->setType("Hebergement")
            ->setEtat("Approuve")
            ->setDateReservation($date)
            ->setIdHebergement($reph->find($heb));
        $em->persist($reservation);
        $em->flush();
        $em->refresh($reservation);
        $dataJson=$normalizer->normalize($reservation->getId(),'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }


    /**
     * @Route ("/DeleteReservationmaisonJSON" ,  name="deleteReservation")
     */
    public function deletereservation(Request $request , NormalizerInterface $normalizer ,ReservationRepository $repr,EntityManagerInterface  $em)
    {



        $reservation= $repr->find($request->get("id"));
        $em->remove($reservation);
        $em->flush();
        $dataJson=$normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }


    /**
     * @Route ("/UpdateReservationmaisonJSON" ,  name="UpdateReservationmaisonMob")
     */
    public function updateReservationmob(Request $request , NormalizerInterface $normalizer ,ReservationRepository $repr,EntityManagerInterface  $em,VoyOrgRepository $repvo)
    {



        $reservation= $repr->find($request->get("id"));
        if($reservation->getType()=="Hebergement")
        {
            $reservation->setEtat("Annulee");
        }
        if($reservation->getType()=="voyageOrganise")
        {
            $voy= $repvo->find($reservation->getIdVoyage()->getIdvoy());
            $voy->setNbrplace($voy->getNbrplace()+$reservation->getNbrPlace());
            $reservation->setEtat("Annulee");
        }

        $em->flush();
        $dataJson=$normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }


    /**
     * @Route ("/addReservationVol" ,  name="addrvolmobile")
     */
    public function addvolmobile(Request $request , NormalizerInterface $normalizer , EntityManagerInterface  $em,VolRepository $repvoy,UserRepository $repuser){

        $voy= $repvoy->find($request->get("idv"));
        $voy->setNbrPlacedispo($voy->getNbrPlacedispo()-$request->get("nbplace"));
        $client=$repuser->find($request->get("idclient"));
        $reservation = new Reservation();
        $reservation->setNbrPlace($request->get("nbplace"));
        $reservation->setIdClient($client);
        $reservation->setIdVol($voy);
        $date  = new \DateTime('@' . strtotime('now'));
        $reservation->setDateReservation( $date);
        $reservation->setDateDebut($voy->getDateDepart());
        $reservation->setDateFin($voy->getDateArrivee());
        $reservation->setType("Vol");
        $reservation->setEtat("Approuve");
        $em->persist($reservation);
        $em->flush();
        $em->refresh($reservation);
        $dataJson=$normalizer->normalize($reservation->getId(),'json',['groups'=>'reservation']);
        return new Response(json_encode($dataJson));
    }
}