<?php

namespace App\Controller;

use App\Entity\Categorievoy;
use App\Entity\Voyageorganise;
use App\Form\VoyageorganiseType;
use App\Repository\VoyOrgRepository;
use App\Repository\categVoyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/voyageorganise")
 */
class VoyageorganiseController extends AbstractController
{
    /**
     * @Route("/back", name="app_voyageorganise_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $voyageorganises = $entityManager
            ->getRepository(Voyageorganise::class)
            ->findAll();

        return $this->render('voyageorganise/index.html.twig', [
            'voyageorganises' => $voyageorganises,
        ]);
    }

    /**
     * @Route("/front", name="app_voyageorganisefront_index", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager): Response
    {

        $voyageorganises = $entityManager
            ->getRepository(Voyageorganise::class)
            ->findAll();

        return $this->render('voyageorganise/index_front.html.twig', [
            'voyageorganises' => $voyageorganises,
        ]);
    }



    /**
     * @Route ("/stat/{id}", name="statup")
     */

    /**
     * @Route ("/stats/", name="statistiquevoy", methods={"GET", "POST"})
     */
    public function stat(VoyOrgRepository $rep)
    {
        $Voyageorganise = $rep->stat();
        $type = [];
        $nbre= [];
        foreach($Voyageorganise as $voy){

            $type [] = $voy['type'];
            $nbre[] = $voy['count'];
        }

        return $this->render('Voyageorganise/stat.html.twig',[
            'type'=> json_encode($type),'nbre'=>json_encode($nbre),
        ]);
    }



    /**
     * @Route("/pdf/{idvoy}", name="pdf")
     */
    public function pdf($idvoy): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        $rep=$this->getDoctrine()->getRepository(Voyageorganise::class);

        $voyageorganise =$rep-> findByidvoy($idvoy);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('voyageorganise/pdf.html.twig', [
            'voyageorganises' => $voyageorganise
        ]);

        $options = new Options();

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/search", name="ajax_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Voyage = $request->get('q');
        $Voyageorganise=$em->getRepository(Voyageorganise::class)->findByvilledest($Voyage);
        if(!$Voyageorganise ) {
            $result['Voyageorganise ']['error'] = "voyage introuvable :( ";
        } else {
            $result['Voyageorganise  '] = $this->getRealEntities($Voyageorganise );
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($Voyageorganise ){
        foreach ($Voyageorganise  as $voyorg ){
            $realEntities[$voyorg ->getIdvoy()] = [$voyorg->getVilledepart(),$voyorg->getVilledest(),$voyorg->getDatedepart(), $voyorg->getDatearrive(), $voyorg->getNbrplace(), $voyorg->getIdcat(), $voyorg->getDescription()];
        }
        return $realEntities;
    }






    /**
     * @Route("/new", name="app_voyageorganise_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyageorganise = new Voyageorganise();
        $form = $this->createForm(VoyageorganiseType::class, $voyageorganise);
        dump($voyageorganise);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyageorganise);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyageorganise/new.html.twig', [
            'voyageorganise' => $voyageorganise,
            'form' => $form->createView(),
        ]);
    }
/*
    /**
     * @Route("/{idvoy}", name="app_voyageorganise_show", methods={"GET"})
     */
   // public function show(Voyageorganise $voyageorganise): Response
   /* {
        //return $this->render('voyageorganise/show.html.twig', [
            'voyageorganise' => $voyageorganise,
        ]);
    }*/

    /**
     * @Route("/{idvoy}/edit", name="app_voyageorganise_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Voyageorganise $voyageorganise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageorganiseType::class, $voyageorganise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyageorganise/edit.html.twig', [
            'voyageorganise' => $voyageorganise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idvoy}", name="app_voyageorganise_delete", methods={"POST"})
     */
    public function delete(Request $request, Voyageorganise $voyageorganise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyageorganise->getIdvoy(), $request->request->get('_token'))) {
            $entityManager->remove($voyageorganise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyageorganise_index', [], Response::HTTP_SEE_OTHER);
    }

    //********************mobile
    /**
     * @Route("/getallVoyage")
     */
    public function getvoyage (VoyOrgRepository $repository , SerializerInterface  $serializer,EntityManagerInterface $entityManager)
    {
        $p = $entityManager
            ->getRepository(Voyageorganise::class)
            ->findAll();

        $dataJson=$serializer->serialize($p,'json',['groups'=>'voyage']);
        return new JsonResponse(json_decode($dataJson) );

    }
    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return void
     * @Route("/addRecJson",name="addRecJson")
     */
    public function addRecJson(Request $request)
    {
        $Voyagorganise=new voyageorganise();

        $villedepart=$request->query->get("villedepart");
        $villedest=$request->query->get("villedest");
        $datedep=$request->query->get("datedepart");
        $datearrive=$request->query->get("datearrive");
        $nbrplace=$request->query->get("nbrplace");
        $description=$request->query->get("description");
        $idcat=$request->query->get("idcat");
        $prix=$request->query->get("prix");

        $em=$this->getDoctrine()->getManager();

        $Voyagorganise->setVilledepart($villedepart);
        $Voyagorganise->setVilledest($villedest);
        $dated= new \DateTime($datedep);
        $datea= new \DateTime($datearrive);
        $Voyagorganise->setDatedepart($dated);
        $Voyagorganise->setDatearrive($datea);
        $Voyagorganise->setNbrplace($nbrplace);
        $Voyagorganise->setDescription($description);
        $Voyagorganise->setPrix($prix);
        $Voyagorganise->setIdcat($this->getDoctrine()->getManager()->getRepository(Categorievoy::class)->find($idcat));

        $em->persist($Voyagorganise);
        $em->flush();
        $encoder=new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object;
        });
        $serializer=new Serializer([$normalizer],[$encoder]);
        $formatted =$serializer->normalize($Voyagorganise);
        return new JsonResponse($formatted);
    }


}
