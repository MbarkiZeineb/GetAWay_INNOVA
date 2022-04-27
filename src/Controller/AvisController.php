<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Entity\Avis;
use App\Entity\User;

use App\Form\AvisType;
use App\Services\SmsService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\GetUser;

/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
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
            if($user)
            $smsService->sendSms("+216".$user->getNumtel(),
                "Votre avis à été bien enregistrer"
            );
        }

        return $this->render('activite/show_front.html.twig', [
            'activite'=>$activite,
            'listavis'=>$listavis,
            'form' => $form->createView(),
        ]);
    }


}
