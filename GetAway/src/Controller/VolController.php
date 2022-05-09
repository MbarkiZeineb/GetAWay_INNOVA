<?php

namespace App\Controller;

use App\Entity\Avion;
use App\Entity\Vol;
use App\Form\VolType;
use App\Repository\AvionRepository;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/vol")
 */
class VolController extends AbstractController
{
    /**
     * @Route("/affiche", name="app_vol_index1", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {


        $vols = $entityManager
            ->getRepository(Vol::class)
            ->findAll();
        $vols =$paginator->paginate(
            $vols,
            $request->query->getInt('page',1),
            3
        );


        return $this->render('vol/index1.html.twig', [
            'vols' => $vols,
        ]);
    }
    /**
     *@Route("/trierVol/{id}", name="sortedVol")
     */
    public function TriA(VolRepository $rep,$id,Request $request, PaginatorInterface $paginator)
    {

        $vols=$rep->TriA();
        $vols =$paginator->paginate(
            $vols,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('vol/index1.html.twig', [
            'vols' => $vols
        ]);

    }

    /**
     * @Route("/V", name="app_vol_index", methods={"GET"})
     */
    public function index1(EntityManagerInterface $entityManager): Response
    {

        $vols = $entityManager
            ->getRepository(Vol::class)
            ->findAll();


        return $this->render('vol/index.html.twig', [
            'vols' => $vols,
        ]);
    }

    /**
     * @Route("/new", name="app_vol_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,VolRepository $volRepository): Response
    {

        $vol = new Vol();
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if (($vol->getDuration())>=1){
                $entityManager->flush();
                $this->addFlash(
                    'warning',
                    "Impossible de créer un vol qui dure plus que 24h."
                );
            }else {
                    $entityManager->persist($vol);
                    $entityManager->flush();
                    $this->addFlash('info', 'Vol ajouté avec succès');
                    return $this->redirectToRoute('app_vol', ['ida' => $vol->getIdAvion()->getIdAgence()->getId()]);


            }
        }

        return $this->render('vol/new.html.twig', [
            'vol' => $vol,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idVol}/edit", name="app_vol_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($vol->getDuration())>=1){
                $entityManager->flush();
                $this->addFlash(
                    'warning',
                    "Impossible de créer un vol qui dure plus que 24h."
                );
            }else {
            $entityManager->flush();
            $this->addFlash('info', 'Vol modifié avec succès');
            return $this->redirectToRoute('app_vol',['ida'=>$vol->getIdAvion()->getIdAgence()->getId()]);
        }}

        return $this->render('vol/edit.html.twig', [
            'vol' => $vol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idVol}", name="app_vol_delete", methods={"POST"})
     */
    public function delete(Request $request, Vol $vol,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vol->getIdVol(), $request->request->get('_token'))) {
            $entityManager->remove($vol);
            $entityManager->flush();
            $this->addFlash('info', 'Vol supprimé avec succès');
        }

        return $this->redirectToRoute('app_vol',['ida'=>$vol->getIdAvion()->getIdAgence()->getId()]);
    }


    /**
     * @Route ("/stat/{id}", name="statup")
     */

    public function statistiques(VolRepository $volRepository,$id)
    {
        $forum = $volRepository->countByDate();
        $date = [];
        $annoncesCount = [];
        foreach($forum as $foru){

            $date [] = $foru['date'];
            $annoncesCount[] = $foru['count'];
        }
        return $this->render('vol/stat.html.twig', [
            'date' => $date,
            'annoncesCount' => $annoncesCount
        ]);
    }


    /**
     * @Route("/ImprimerV/{id}", name="ImprimerV")
     */
    public function ImprimerVol($id){
        $repository=$this->getDoctrine()->getRepository(Vol::class);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $vol=$repository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('vol/listevol.html.twig',
            ['vol'=>$vol]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("listevol.pdf", [
            "Attachment" => true
        ]);


    }


    //********************mobile
    /**
     * @Route("/getallVol",name="getvol")
     */
    public function getvoyage (VolRepository $repository , SerializerInterface  $serializer)
    {
        $p = $repository->findAll();
        $dataJson=$serializer->serialize($p,'json',['groups'=>'vol']);
        return new JsonResponse(json_decode($dataJson) );

    }





}
