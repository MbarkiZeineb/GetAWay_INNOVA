<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/hebergement")
 */
class HebergementController extends AbstractController
{
    /**
     * @Route("/mobile/edit",name="edit_mobile_heb")
     */
    public function editHeb(Request $request)
    {
        $reference = $request->query->get("reference");
        $paye = $request->query->get('paye');
        $adress = $request->query->get('adress');
        $prix = $request->query->get('prix');
        $description = $request->query->get('description');
        $photo = $request->query->get('photo');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');
        $contact = $request->query->get('contact');
        $nbrDetoile = $request->query->get('nbrDetoile');
        $nbrSuite = $request->query->get('nbrSuite');
        $nbrParking = $request->query->get('nbrParking');
        $modelCaravane = $request->query->get('modelCaravane');

        $firstDate = new DateTime($dateStart);
        $secondDate = new DateTime($dateEnd);
        $hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->findOneBy(['referance' => $reference]);
        $hebergement->setDescription($description);
        $hebergement->setPaye($paye);
        $hebergement->setAdress($adress);
        $hebergement->setPrix($prix);
        $hebergement->setPhoto($photo);
        $hebergement->setDateStart($firstDate);
        $hebergement->setDateEnd($secondDate);
        $hebergement->setContact($contact);
        $hebergement->setNbrDetoile($nbrDetoile);
        $hebergement->setNbrSuite($nbrSuite);
        $hebergement->setNbrParking($nbrParking);
        $hebergement->setModelCaravane($modelCaravane);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new JsonResponse("hebergement edited");
        } catch (Exception $e) {
            return new JsonResponse("error while editing hebergement");
        }
    }

    /**
     * @Route("/back", name="app_hebergementB", methods={"GET"})
     */
    public function index1(EntityManagerInterface $entityManager): Response
    {
        $hebergements = $entityManager
            ->getRepository(Hebergement::class)
            ->findAll();

        return $this->render('hebergement/hebergementB.html.twig', [
            'hebergements' => $hebergements,
        ]);
    }

    /**
     * @Route("/", name="app_hebergement_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $hebergements = $entityManager
            ->getRepository(Hebergement::class)
            ->findAll();

        return $this->render('hebergement/index.html.twig', [
            'hebergements' => $hebergements,
        ]);
    }

    /**
     * @Route("/new", name="app_hebergement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement/new.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{referance}", name="app_hebergement_show", methods={"GET"})
     */
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('hebergement/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }


    /**
     * @Route("/{referance}/edit", name="app_hebergement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{referance}", name="app_hebergement_delete", methods={"POST"})
     */
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hebergement->getReferance(), $request->request->get('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/trier/{id}", name="sortedVol")
     */
    public function TriA(HebergementRepository $rep, $id, Request $request)
    {

        $hebergement = $rep->TriA();


        return $this->render('hebergement/hebergementB.html.twig', [
            'hebergements' => $hebergement
        ]);

    }

    /**
     * @Route ("/{referance}/QrcodeR", name="QrcodeHebergement", methods={"GET", "POST"})
     */
    public function QrCode($referance, HebergementRepository $rep)
    {
        $hebergement = $rep->find($referance);

        $data = $hebergement->show() . ' Montant total ';
        $result = Builder::create()
            ->encoding(new Encoding('UTF-8'))
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText($hebergement->getReferance())
            ->build();

        // Directly output the QR code
        header('Content-Type: ' . $result->getMimeType());
        echo $result->getString();

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        return $this->render("hebergement/qrcode.html.twig", ['data' => $dataUri]);

    }

    /**
     * @Route("/mobile/add", name="add_mobile_hebergement")
     * @throws Exception
     */
    public function addHeb(Request $request)
    {
        $paye = $request->query->get('paye');
        $adress = $request->query->get('adress');
        $prix = $request->query->get('prix');
        $description = $request->query->get('description');
        $photo = $request->query->get('photo');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');
        $contact = $request->query->get('contact');
        $nbrDetoile = $request->query->get('nbrDetoile');
        $nbrSuite = $request->query->get('nbrSuite');
        $nbrParking = $request->query->get('nbrParking');
        $modelCaravane = $request->query->get('modelCaravane');
        $idCateg = $request->query->get('idCateg');

        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['idCateg' => $idCateg]);

        $hebergement = new Hebergement();
        try {
            $firstDate = new DateTime($dateStart);
            $secondDate = new DateTime($dateEnd);
            $hebergement->setDescription($description);
            $hebergement->setPaye($paye);
            $hebergement->setAdress($adress);
            $hebergement->setPrix($prix);
            $hebergement->setPhoto($photo);
            $hebergement->setDateStart($firstDate);
            $hebergement->setDateEnd($secondDate);
            $hebergement->setContact($contact);
            $hebergement->setNbrDetoile($nbrDetoile);
            $hebergement->setNbrSuite($nbrSuite);
            $hebergement->setNbrParking($nbrParking);
            $hebergement->setIdCateg($category);
            $hebergement->setModelCaravane($modelCaravane);

            //dd($hebergement);

            $em = $this->getDoctrine()->getManager();
            $em->persist($hebergement);
            $em->flush();
            return new JsonResponse("hebergement added");
        } catch (Exception $e) {
            return new JsonResponse("error while adding hebergement : " . $e->getMessage());
        }
    }

    /**
     * @Route("/mobile/delete", name="delete_mobile_hebergement")
     */
    public function deleteHeb(Request $request)
    {
        $id = $request->query->get('reference');
        $category = $this->getDoctrine()->getRepository(Hebergement::class)->findOneBy(['referance' => $id]);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            return new JsonResponse("heb deleted successfully");
        } catch (Exception $e) {
            return new JsonResponse("error on " . $e->getMessage());
        }
    }
    /**
     * @Route("/mobile/getAll",name="get_mobile_heb")
     */
    public function showHeb(): Response
    {
        $hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->findAll();
        $hebergementList = [];
        foreach ($hebergement as $cat) {
            $hebergementList[] = [
                'reference' => $cat->getReferance(),
                'paye' => $cat->getPaye(),
                'adress' => $cat->getAdress(),
                'prix' => $cat->getPrix(),
                'description' => $cat->getDescription(),
                'dateStart' => $cat->getDateStart()->format('y-m-d'),
                'dateEnd' => $cat->getDateEnd()->format('y-m-d'),
                'contact' => $cat->getContact(),
                'nbrDetoile' => $cat->getNbrDetoile(),
                'nbrSuite' => $cat->getNbrSuite(),
                'nbrParking' => $cat->getNbrParking(),
                'modelCaravane' => $cat->getModelCaravane(),
                'photo' => $cat->getPhoto(),
            ];
        }
        return new Response(json_encode($hebergementList));
    }




}
