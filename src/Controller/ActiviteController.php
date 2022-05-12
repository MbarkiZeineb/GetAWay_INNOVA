<?php

namespace App\Controller;
use App\Entity\Activitelike;
use App\Entity\Avis;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActivitelikeRepository;
use App\Repository\ActiviteRepository;

use CalendarBundle\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\countOf;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    //Mobile
    /**
     * @Route ("/actgetting")
     */
    public function getactivite(): Response
    {
        $activite = $this->getDoctrine()->getRepository(Activite::class)->findAll();
        $activiteList = [];
        foreach ($activite as $act){
            $activiteList[] = [
                'refact' => $act->getRefAct(),
                'nom' => $act->getNom(),
                'descrip' => $act->getDescrip(),
                'duree' => $act->getDuree(),
                'nbrplace' => $act->getNbrPlace(),
                'date' => $act->getDate()->format('y-m-d'),
                'type' => $act->getType(),
                'location' => $act->getLocation(),
                'prix' => $act->getPrix(),
                'image' => $act->getImage(),
            ];
        }
        return new Response(json_encode($activiteList));
    }
    /**
     * @Route ("/help")
     */

    public function help(EntityManagerInterface $entityManager)
    {
        $act=$entityManager->getRepository(Activite::class)->findNbrplacedispo();
        $act2=$entityManager->getRepository(Activite::class)->findNbrAct();

        if($act2<10 && $act>0){
            return new JsonResponse("Il y a $act activites avec 0 place disponible. Total des activites: $act2");
        }
        else
            if($act>0) {
                return new JsonResponse("Il y a $act activites avec 0 place disponible");
            }
            else
                if ($act2<6)
                    return new JsonResponse("Total activite disponible: $act2 activites");


    }
//Web

    /**
     * @Route("/", name="app_activite_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $activites = $entityManager
            ->getRepository(Activite::class)
            ->findAll();
        $act = $entityManager->getRepository(Activite::class)->findNbrplacedispo();
        $act2 = $entityManager->getRepository(Activite::class)->findNbrAct();
        dump($act);
        dump($act2);


        if ($act2 < 10 && $act > 0) {
            $this->flashy->error("Vous avez '$act' activites avec 0 place disponible et Total des activites: $act2");
        } else
            if ($act > 0) {
                $this->flashy->error("Vous avez '$act' activites avec 0 place disponible");
            } else
                if ($act2 < 6)
                    $this->flashy->error("Vous avez que '$act2' activites");

        return $this->render('activite/index.html.twig', [
            'activites' => $activites,
        ]);

    }

    /**
     * @Route("/stats",name="stats")
     */
    public function stats()
    {


        $sportArray = $this->getDoctrine()->getRepository(Activite::class)->findBy(
            ['type' => 'Sport']);
        $sportArraySize = sizeof($sportArray);


        $educativeArray = $this->getDoctrine()->getRepository(Activite::class)->findBy(
            ['type' => 'Educative']);
        $educativeArraySize = sizeof($educativeArray);


        $loisirArray = $this->getDoctrine()->getRepository(Activite::class)->findBy(
            ['type' => 'Loisir']);
        $loisirArraySize = sizeof($loisirArray);


        $aventureArray = $this->getDoctrine()->getRepository(Activite::class)->findBy(
            ['type' => 'Aventure']);
        $aventureArraySize = sizeof($aventureArray);


        return $this->render('activite/stats.html.twig', [
            'aventureArraySize' => $aventureArraySize, 'loisirArraySize' => $loisirArraySize,
            'educativeArraySize' => $educativeArraySize, 'sportArraySize' => $sportArraySize


        ]);
    }


    /**
     * @Route("/f", name="app_activite_indexfront", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $activites = $entityManager
            ->getRepository(Activite::class)
            ->findAll();
        return $this->render('activite/index_front.html.twig', [
            'activites' => $activites,
        ]);

    }

    /**
     * @Route("/new", name="app_activite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();
            $this->flashy->success('Activite ajouter');
            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }


        $this->flashy->error("L'Ajout de l'activite à echouée");
        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refact}", name="app_activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        $this->flashy->info("Détails de l'activité");
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/actf/{refact}", name="app_activite_showfront", methods={"GET"})
     */
    public function showFront(Activite $activite): Response
    {

        return $this->render('activite/show_front.html.twig', [
            'activite' => $activite,


        ]);
    }

    /**
     * @Route("/{refact}/edit", name="app_activite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->flashy->success("Modification avec succées");
            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->flashy->error("Modification echoué");
        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{refact}", name="app_activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activite->getRefact(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }
        $this->flashy->success("Suppression avec succées");
        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }


    public function __toString()
    {
        return $this->nom;
    }

    /**
     * Ajout/supp d'un like
     * @Route ("/{id}/like", name="activite_like")
     *
     * @param Activite $activite
     * @param EntityManagerInterface $manager
     * @param ActivitelikeRepository $likerepo
     * @return Response
     */
    public function like(Activite $activite, EntityManagerInterface $manager, ActivitelikeRepository $likeRepo)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['code' => 403, 'error' => 'Vous devez être connecté !'], 403);
        }

        if ($activite->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy(['act' => $activite, 'user' => $user]);

            $manager->remove($like);
            $manager->flush();

            return $this->json(['code' => 200, 'likes' => $likeRepo->getCountForPost($activite)], 200);
        }

        $like = new Activitelike();
        $like->setAct($activite)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json(['code' => 200, 'likes' => $likeRepo->getCountForPost($activite)], 200);
    }

}