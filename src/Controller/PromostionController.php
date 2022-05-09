<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Hebergement;
use App\Entity\Promostion;
use App\Form\PromostionType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/promostion")
 */
class PromostionController extends AbstractController
{

    /**
     * @Route("/mobile/edit",name="edit_mobile_heb")
     */
    public function editHeb(Request $request)
    {
        $id = $request->query->get('idRef');
        $promo = $this->getDoctrine()->getRepository(Promostion::class)->findOneBy(['idRef' => $id]);
        $pourcentage = $request->query->get('pourcentage');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');

        $oldPourcentage = $promo->getPourcentage();

        $firstDate = new \DateTime($dateStart);
        $secondDate = new \DateTime($dateEnd);

        $promo->getRefHebergement()->setPrix(($promo->getRefHebergement()->getPrix()*100)/(100-$oldPourcentage));
        $promo->getRefHebergement()->setPrix(($promo->getRefHebergement()->getPrix()*(100-$promo->getPourcentage()))/100);

        $promo->setPourcentage($pourcentage);
        $promo->setDateStart($firstDate);
        $promo->setDateEnd($secondDate);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new JsonResponse("promo edited");
        } catch (Exception $e) {
            return new JsonResponse("error while editing promo");
        }
    }

    /**
     * @Route("/", name="app_promostion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {
        $allpromostions = $entityManager
            ->getRepository(Promostion::class)
            ->findAll();
        $promostions = $paginator->paginate(
        // Doctrine Query, not results
            $allpromostions,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('promostion/index.html.twig', [
            'promostions' => $promostions,
        ]);
    }

    /**
     * @Route("/new", name="app_promostion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promostion = new Promostion();
        $form = $this->createForm(PromostionType::class, $promostion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $promostion->getRefHebergement()->setPrix(($promostion->getRefHebergement()->getPrix()*(100-$promostion->getPourcentage()))/100);
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($promostion);
            $entityManager->flush();

            return $this->redirectToRoute('app_promostion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promostion/new.html.twig', [
            'promostion' => $promostion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRef}", name="app_promostion_show", methods={"GET"})
     */
    public function show(Promostion $promostion): Response
    {
        return $this->render('promostion/show.html.twig', [
            'promostion' => $promostion,
        ]);
    }

    /**
     * @Route("/{idRef}/edit", name="app_promostion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promostion $promostion, EntityManagerInterface $entityManager): Response
    {
        $oldPourcentage = $promostion->getPourcentage();
        $form = $this->createForm(PromostionType::class, $promostion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$old = ($promostion->getRefHebergement()->getPrix()*100)/(100-$promostion->getPourcentage());
            //$new = $old *(100-$promostion->getPourcentage());
            $promostion->getRefHebergement()->setPrix(($promostion->getRefHebergement()->getPrix()*100)/(100-$oldPourcentage));
            $promostion->getRefHebergement()->setPrix(($promostion->getRefHebergement()->getPrix()*(100-$promostion->getPourcentage()))/100);

            $entityManager->flush();

            return $this->redirectToRoute('app_promostion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promostion/edit.html.twig', [
            'promostion' => $promostion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRef}", name="app_promostion_delete", methods={"POST"})
     */
    public function delete(Request $request, Promostion $promostion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promostion->getIdRef(), $request->request->get('_token'))) {
            $promostion->getRefHebergement()->setPrix(($promostion->getRefHebergement()->getPrix()*100)/(100-$promostion->getPourcentage()));

            $entityManager->remove($promostion);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_promostion_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/mobile/add", name="add_mobile_promo")
     */
    public function addPromo(Request $request)
    {
        $pourcentage = $request->query->get('pourcentage');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');
        $refHeb = $request->query->get('refHebergement');
        $hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->findOneBy(['referance' => $refHeb]);

        $firstDate = new \DateTime($dateStart);
        $secondDate = new \DateTime($dateEnd);

        $promotion = new Promostion();
        $promotion->setPourcentage($pourcentage);
        $promotion->setDateEnd($secondDate);
        $promotion->setDateStart($firstDate);
        $promotion->setRefHebergement($hebergement);
        $promotion->getRefHebergement()->setPrix(($promotion->getRefHebergement()->getPrix()*(100-$promotion->getPourcentage()))/100);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            return new JsonResponse("promo added");
        } catch (Exception $e) {
            return new JsonResponse("error while adding promo : " . $e->getMessage());
        }
    }


    /**
     * @Route("/mobile/delete", name="delete_mobile_promo")
     */
    public function deletepromo(Request $request)
    {
        $id = $request->query->get('idRef');
        $promo = $this->getDoctrine()->getRepository(Promostion::class)->findOneBy(['idRef' => $id]);
        $promo->getRefHebergement()->setPrix(($promo->getRefHebergement()->getPrix()*100)/(100-$promo->getPourcentage()));

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($promo);
            $em->flush();
            return new JsonResponse("promo deleted successfully");
        } catch (Exception $e) {
            return new JsonResponse("error on " . $e->getMessage());
        }
    }

    /**
     * @Route("/mobile/getAll",name="get_mobile_promo")
     */
    public function showPromo(): Response
    {
        $promo=$this->getDoctrine()->getRepository(Promostion::class)->findAll();
        $promoList =[];
        foreach ($promo as $cat ){
            $promoList[] = [
                'idRef' => $cat->getIdRef(),
                'pourcentage' => $cat->getPourcentage(),
                'dateStart' => $cat->getDateStart()->format('y-m-d'),
                'dateEnd' => $cat->getDateEnd()->format('y-m-d')
            ];

        }
        return new Response(json_encode($promoList));

    }
}
