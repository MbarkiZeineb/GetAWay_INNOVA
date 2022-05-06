<?php

namespace App\Controller;
use App\Entity\Paiement;
use App\Form\ModifierPType;
use App\Form\PaiementType;
use App\Form\ReservationVoyType;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Repository\VoyageorganiseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


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
     * @Route("/newvo/{id}/{prix}", name="app_paiement_newvo", methods={"GET", "POST"})
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
            if($paiement->getModalitePaiement()=="CARTE")
            {    $user=$reservation->getIdClient()->getNom().'  '.$reservation->getIdClient()->getPrenom();
                $produit=$reservation->getType().' Ville de arrrive : '.$reservation->getIdVoyage()->getVilledest().' : '.'  Date de depart :'.$reservation->getIdVoyage()->getVilledest();
                Stripe::setApiKey($_ENV['STRIPE_SK']);
                $session =Session::create([
                    'payment_method_types'=>['card'],
                    'line_items'=>[[
                        'price_data'=>[
                            'currency'=>'usd',
                            'product_data'=>[
                                'name'=>$produit,

                            ],
                            'unit_amount'=>$paiement->getMontant(),
                        ],
                        'quantity'=>$reservation->getNbrPlace(),
                    ]],
                    'mode'=>'payment',
                    'success_url'=>$this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url'=>$this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
                dump($session->id);
                return $this->redirect($session->url, 303);
            }

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
            if($paiement->getModalitePaiement()=="CARTE")
            {    $user=$reservation->getIdClient()->getNom().'  '.$reservation->getIdClient()->getPrenom();
                $produit=$reservation->getType().' Ville de arrrive : '.$reservation->getIdVoyage()->getVilledest().' : '.'  Date de depart :'.$reservation->getIdVoyage()->getVilledest();
                Stripe::setApiKey($_ENV['STRIPE_SK']);
                $session =Session::create([
                    'payment_method_types'=>['card'],
                    'line_items'=>[[
                        'price_data'=>[
                            'currency'=>'usd',
                            'product_data'=>[
                                'name'=>$produit,

                            ],
                            'unit_amount'=>$paiement->getMontant(),
                        ],
                        'quantity'=>$reservation->getNbrPlace(),
                    ]],
                    'mode'=>'payment',
                    'success_url'=>$this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url'=>$this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
                dump($session->id);
                return $this->redirect($session->url, 303);
            }
            return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdHebergement()->getReferance()));
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="app_paiement_show", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController'
        ]);
    }
    /**
     * @Route("/newvol/{id}/{prix}", name="app_paiement_newvol", methods={"GET", "POST"})
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
            if($paiement->getModalitePaiement()=="CARTE") {
                $user = $reservation->getIdClient()->getNom() . '  ' . $reservation->getIdClient()->getPrenom();
                $produit = $reservation->getType() . ' Ville de arrrive : ' . $reservation->getIdVol()->getVilleArrivee() . ' : ' . '  Date de depart :' . $reservation->getIdVol()->getDateDepart();
                Stripe::setApiKey($_ENV['STRIPE_SK']);
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $produit,

                            ],
                            'unit_amount' => $paiement->getMontant(),
                        ],
                        'quantity' => $reservation->getNbrPlace(),
                    ]],
                    'mode' => 'payment',
                    'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
                dump($session->id);
                return $this->redirect($session->url, 303);
            }
            else{
                return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdVol()->getIdVol()));
            }
        }
        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cancel_url", name="cancel_url")
     */
    public function cancelurl(): Response
    {
        return $this->redirectToRoute('cart_panier');
    }

    /**
     * @Route("/DetailsR/{id}", name="detailsR", methods={"GET","POST"})
     */
    public function showP($id,PaiementRepository $rep): Response
    {
        $paiement=$rep->showPaiement($id);


        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    /**
     * @Route("/DetailsRFront/{id}", name="detailsRFront" , methods={"GET", "POST"})
     */
    public function showPFront(Request $request,$id,PaiementRepository $rep,EntityManagerInterface $entityManager): Response
    {
        $paiement=$rep->showPaiement($id);
        $form = $this->createForm(ModifierPType::class, $paiement);
        $form->handleRequest($request);
        dump($paiement);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            dump("aaaa");
            dump($paiement);
            return $this->redirectToRoute('AfficherClient');



        }
        return $this->render('paiement/PaiementFront.html.twig', [
            'paiement' => $paiement,'form'=>$form->createView()
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

/**
* @Route("/newAct/{id}/{prix}", name="app_paiement_newAct", methods={"GET", "POST"})
*/
public function newact(Request $request, EntityManagerInterface $entityManager,$prix,$id,ReservationRepository $repR): Response
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
        dump("aa");
        if($paiement->getModalitePaiement()=="CARTE")
        {       dump("test");
            $produit=$reservation->getType().'  nom : '.$reservation->getIdActivite()->getNom().' : '.'  Date  :'.$reservation->getIdActivite()->getDate();
                Stripe::setApiKey($_ENV['STRIPE_SK']);
                $session =Session::create([
                    'payment_method_types'=>['card'],
                    'line_items'=>[[
                        'price_data'=>[
                            'currency'=>'usd',
                            'product_data'=>[
                                'name'=>$produit,

                            ],
                            'unit_amount'=>$paiement->getMontant(),
                        ],
                        'quantity'=>$reservation->getNbrPlace(),
                    ]],
                    'mode'=>'payment',
                    'success_url'=>$this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url'=>$this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
            dump($session->id);
            return $this->redirect($session->url, 303);
    }
        else{
            return $this->redirectToRoute('delete_items',array('id'=>$reservation->getIdActivite()->getRefact()));
        }


    }

    return $this->render('paiement/new.html.twig', [
        'paiement' => $paiement,
        'form' => $form->createView(),
    ]);
}
    /**
     * @Route("/newGroup/{id}/{prix}", name="app_paiement_newgroup", methods={"GET", "POST"})
     */
    public function newgroup(Request $request, EntityManagerInterface $entityManager,$prix,$id,ReservationRepository $repR): Response
    {
        $paiement = new Paiement();
        $reservation=$repR->find($id);
        $paiement->setMontant( $prix);
        $paiement->setIdReservation($reservation);
        $date1 = new \DateTime('@'.strtotime('now'));
        $paiement->setDate($date1);
        $form = $this->createForm(PaiementType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            if($paiement->getModalitePaiement()=="CARTE")
            {    $user=$reservation->getIdClient()->getNom().'  '.$reservation->getIdClient()->getPrenom();
                $produit=$reservation->getType().' Ville de arrrive : '.$reservation->getIdVoyage()->getVilledest().' : '.'  Date de depart :'.$reservation->getIdVoyage()->getVilledest();
                Stripe::setApiKey($_ENV['STRIPE_SK']);
                $session =Session::create([
                    'payment_method_types'=>['card'],
                    'line_items'=>[[
                        'price_data'=>[
                            'currency'=>'usd',
                            'product_data'=>[
                                'name'=>$produit,

                            ],
                            'unit_amount'=>$paiement->getMontant(),
                        ],
                        'quantity'=>$reservation->getNbrPlace(),
                    ]],
                    'mode'=>'payment',
                    'success_url'=>$this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url'=>$this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
                dump($session->id);
                return $this->redirect($session->url, 303);
            }
            return $this->redirectToRoute('delete_items_Group',array('idvol'=>$reservation->getIdVol()->getIdVol(),'ida'=>$reservation->getIdActivite()->getRefact(),'idv'=>$reservation->getIdVoyage()->getIdvoy()));
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success_url", name="success_url")
     */
    public function succesurl(): Response
    {   dump("test");
        return $this->redirectToRoute('AfficherClient',array('id'=>$this->getUser()->getUsername()));
    }


    //mobile


    /**
     * @Route ("/addpaimentmobile" ,  name="addpaimentvoymobile")
     */
    public function addPaiement(Request $request , NormalizerInterface $normalizer , EntityManagerInterface  $em,ReservationRepository  $repR)

    {
        $paiement = new Paiement();
        $reservation=$repR->find($request->get("idR"));
        $paiement->setMontant($request->get("montant"));
        $paiement->setIdReservation($reservation);
        $paiement->setModalitePaiement($request->get("modep"));
        $date1 = new \DateTime('@'.strtotime('now'));
        $paiement->setDate($date1);
        $em->persist($paiement);
        $em->flush();
        dump("aaaa");
        dump($paiement);
        $dataJson=$normalizer->normalize($paiement,'json',['groups'=>'paiement']);
        return new Response(json_encode($dataJson));

    }

    /**
     * @Route("/GetPaiement", name="GetPaiement")
     */
    public function getPaiement (PaiementRepository $repository , SerializerInterface  $serializer)
    {
        $p = $repository->findAll();
        $dataJson=$serializer->serialize($p,'json',['groups'=>'paiement']);
        // dd($dataJson);
        return new JsonResponse(json_decode($dataJson) );
    }
    /**
     * @Route("/paiementdetailsr", name="detailspaiementr")
     */
    public function getPaimentR (PaiementRepository $repository ,ReservationRepository $repr,SerializerInterface  $serializer , Request $request)
    {     $res=$repr->find($request->get("id"));
        $p = $repository->findBy(['idReservation'=>$res]);
        $dataJson=$serializer->serialize($p,'json',['groups'=>'paiement']);
        // dd($dataJson);
        return new JsonResponse(json_decode($dataJson) );
    }


    }
