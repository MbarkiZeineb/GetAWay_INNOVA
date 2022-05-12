<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Entity\Avis;
use App\Entity\User;

use App\Form\AvisType;
use App\Repository\UserRepository;
use App\Services\SmsService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\GetUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    //Mobile

    /**
     * @Route ("/avisgetting" , name="getavismobile",)
     */
    public function getavis(UserRepository $repuser,Request $request): Response
    {
        $user=$repuser->find($request->get('id'));
        $avis = $this->getDoctrine()->getRepository(Avis::class)->findBy(['id'=>$user]);
        $avisList = [];
        foreach ($avis as $avi){
            $avisList[] = [
                'refavis' => $avi->getRefavis(),
                'message' => $avi->getMessage(),
                'date' => $avi->getDate()->format('d-M-Y'),
                'rating' => $avi->getRating(),
                'id' => $avi->getId()->getNom(),
                'refactivite' => $avi->getRefactivite()->getNom(),
            ];
        }
        return new Response(json_encode($avisList));
    }

    /**
     * @Route ("/addavis")
     */
    public function addavis(Request $request , NormalizerInterface $normalizer, UserRepository $repuser, ActiviteRepository $rep,SmsService $smsService){

        $em=$this->getDoctrine()->getManager();
        $client=$repuser->find($request->get("id"));
        $activite=$rep->find($request->get('refactivite'));
        $avis = new Avis();
        $avis->setMessage($request->get('message'));
        $avis->setRating($request->get('rating'));
        $date1 = new \DateTime('@'.strtotime('now'));
        $avis->setDate($date1);
        $avis->setId($client);
        $avis->setRefactivite($activite);
        $em->persist($avis);
        $em->flush();
        $jsonContent =$normalizer->normalize($avis,'json',['groups'=>'avis']);
        $smsService->sendSms("+21693781904",
            "Votre avis à été bien enregistrer");
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route ("/deleteavis/{id}")
     */
    public function deleteavis(Request $request , NormalizerInterface $normalizer ,$id){

        $em=$this->getDoctrine()->getManager();
        $avis = $em->getRepository(Avis::class)->find($id);

        $em->remove($avis);
        $em->flush();
        $dataJson=$normalizer->normalize($avis,'json',['groups'=>'avis']);
        return new Response("Avis delete successfully".json_encode($dataJson));
    }

    /**
     * @Route("/", name="app_avis_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    /**
     * @Route("/avisf", name="app_avis_indexfront", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        return $this->render('avis/index_front.html.twig', [
            'avis' => $avis,

        ]);
    }


    /**
     * @Route("/new", name="app_avis_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refavis}", name="app_avis_show", methods={"GET"})
     */
    public function show(Avis $avi): Response
    {

        $this->addFlash(
            'info',
            'Details de votre avis'
        );

        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    /**
     * @Route("/{refavis}/edit", name="app_avis_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Modification effectuée'
            );
            return $this->redirectToRoute('app_avis_indexfront', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refavis}", name="app_avis_delete", methods={"POST"})
     */
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getRefavis(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();

        }
        $this->addFlash(
            'info',
            'Suppresion effectuée'
        );
        return $this->redirectToRoute('app_avis_indexfront', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/new/{RefAct}", name="app_avis_act", methods={"GET", "POST"})
     */
    public function addavisAct(Request $request, EntityManagerInterface $entityManager,$RefAct, SmsService $smsService,GetUser $getUser): Response
    {
        $activite = $entityManager->getRepository(Activite::class)->find($RefAct);
        $listavis = $entityManager->getRepository(Avis::class)->findAll();

        $avis = new Avis();
        $avis->setDate(new \DateTime('now'));
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        $avis->setRefactivite($activite);
        $userConnected=$getUser->getUser();
        $user = $entityManager->getRepository(User::class)->find($userConnected);


        $avis->setId($user);

        dump($avis);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($avis);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Avis Ajouter'
            );
            //if($user)
              //  $smsService->sendSms("+216".$user->getNumtel(),
                //    "Votre avis à été bien enregistrer"
                //);
        }

        return $this->render('activite/show_front.html.twig', [
            'activite'=>$activite,
            'listavis'=>$listavis,
            'form' => $form->createView(),
        ]);
    }


}