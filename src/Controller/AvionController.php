<?php

namespace App\Controller;

use App\Entity\Avion;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\AvionType;
use App\Repository\AvionRepository;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/avion")
 */
class AvionController extends AbstractController
{
    /**
     * @Route("/", name="app_avion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avions = $entityManager
            ->getRepository(Avion::class)
            ->findBy(['idAgence'=>$this->getUser()->getUsername()]);

        return $this->render('avion/index.html.twig', [
            'avions' => $avions,
        ]);
    }


    /**
     * @Route("/new/{ida}", name="app_avion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$ida): Response
    { $user= $entityManager->getRepository(User::class)->find($ida);

        $avion = new Avion();
        $avion->setIdAgence($user);
        $form = $this->createForm(AvionType::class, $avion);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $avion->setType($user->getNomagence());

            $entityManager->persist($avion);
            $entityManager->flush();
            $this->addFlash('info', 'Avion ajouté avec succès');

            return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
        }


        return $this->render('avion/new.html.twig', [
            'avion' => $avion,
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/{idAvion}/edit", name="app_avion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Avion $avion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvionType::class, $avion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Avion modifié avec succès');
            return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
        }

        return $this->render('avion/edit.html.twig', [
            'avion' => $avion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idAvion}", name="app_avion_delete", methods={"POST"})
     */
    public function delete(Request $request, Avion $avion,VolRepository $volRepository,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avion->getIdAvion(), $request->request->get('_token'))) {

            $entityManager->remove($avion);
            $entityManager->flush();
            $this->addFlash('info', 'Avion et ses listes des vols sont supprimés avec succès');
        }

        return $this->redirectToRoute('app_avion', ['ida'=>$avion->getIdAgence()->getId()]);
    }

    /**
     *@Route("/trieravion/{id}", name="sortedAvion")
     */
    public function TriA(AvionRepository $rep,$id)
    {

        $avion=$rep->TriA();

        return $this->render('avion/index.html.twig', [
            'avions' => $avion
        ]);

    }

    /**
     * @Route("/affichevol/{ida}", name="app_vol", methods={"GET"})
     */
    public function listvolByid(UserRepository $userRepository,VolRepository $volRepository, AvionRepository $avionRepository,$ida): Response
    {

        $user=$userRepository->find($ida);
        $avion=$avionRepository->findBy(['idAgence'=>$user]);

        $vol=$volRepository->listByidv($user->getId());
        return $this->render('vol/index.html.twig', [
            'user'=>$user,
            'vols' => $vol,
        ]);
    }


    //********************mobile
    /**
     * @Route("/getallAvion",name="getavion")
     */
    public function getvoyage (AvionRepository $repository , SerializerInterface  $serializer)
    {
        $p = $repository->findAll();
        $dataJson=$serializer->serialize($p,'json',['groups'=>'avion']);
        return new JsonResponse(json_decode($dataJson) );

    }

    //***************************************Mobile*******

    /**
     * @Route ("/addavion" ,  name="addavion")
     */
    public function addavion(Request $request , NormalizerInterface $normalizer ){

        $em=$this->getDoctrine()->getManager();
        $avion = new Avion();
        $avion->setNbrPlace($request->get('nbrPlace'));
        $avion->setNomAvion($request->get('nomAvion'));
        //$avion->setIdAgence($request->get('idAgence'));

        $em->persist($avion);
        $em->flush();
        $jsonContent =$normalizer->normalize($avion,'json',['groups'=>'avion']);
        return new Response(json_encode($jsonContent));
    }

    //***************************************Mobile*******

    /**
     * @Route ("/deleteavion/{id}" ,  name="deleteavion", methods={"GET", "POST"})
     */
    public function deleteavion(Request $request , NormalizerInterface $normalizer ,$id){

        $em=$this->getDoctrine()->getManager();
        $avion = $em->getRepository(Avion::class)->find($id);

        $em->remove($avion);
        $em->flush();
        $dataJson=$normalizer->normalize($avion,'json',['groups'=>'avion']);
        return new Response("avion delete successfully".json_encode($dataJson));
    }



}