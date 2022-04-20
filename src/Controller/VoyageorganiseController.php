<?php

namespace App\Controller;

use App\Entity\Voyageorganise;
use App\Form\VoyageorganiseType;
use App\Repository\VoyOrgRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/stats", name="stats")
     */
    public function statistiques(){
        // On va chercher toutes les voyages
        $rep=$this->getDoctrine()->getRepository(Voyageorganise::class);
        $Voyageorganise = $rep->findAll();


        $categNom = [];
        $categColor = [];
        $categCount = [];

        foreach ($Voyageorganise  as $voyorg ){


            $categNom[] = $voyorg->getNomCategorie();
            $categColor[] = $voyorg->getColor();
            $categCount[] = count($voyorg->getOffres());

        }
        return $this->render('voyageorganise/stat.html.twig', [




            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount)

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

    /**
     * @Route("/{idvoy}", name="app_voyageorganise_show", methods={"GET"})
     */
    public function show(Voyageorganise $voyageorganise): Response
    {
        return $this->render('voyageorganise/show.html.twig', [
            'voyageorganise' => $voyageorganise,
        ]);
    }

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
}
