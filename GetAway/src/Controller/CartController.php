<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationGroupType;
use App\Repository\ActiviteRepository;
use App\Repository\HebergementRepository;
use App\Repository\ReservationRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageorganiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Voyageorganise;
use function PHPUnit\Framework\isEmpty;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_panier")
     */
    public function index(SessionInterface $s, VoyageorganiseRepository $rep,Request $requuest,HebergementRepository $reph, VolRepository $repvol, ActiviteRepository $repa): \Symfony\Component\HttpFoundation\Response
    {   $panier = $s->get('panier', []);
        $voyage[] = [];
        $vol[] = [];
        $heb[] = [];
        $act[] = [];
        $var[] = [];
        foreach ($panier as $id => $value) {
            if($rep->find($id))
            $voyage[] = ['voyage' =>$rep->find($id)];
            if($repvol->find($id))
            $vol[] = ['vol' => $repvol->find($id)];
            if($reph->find($id))
            $heb[] = ['heb' => $reph->find($id)];
            if($repa->find($id))
            $act[] = ['act' => $repa->find($id)];
        }
        unset($voyage[0]);
        unset($vol[0]);
        unset($act[0]);



        $form = $this->createFormBuilder($var)
            ->add('nbrPlace', NumberType::class)
            ->add('submit', SubmitType::class, array(
                    'label' => 'Submit',
                    'disabled' => !$this->check($s,$rep,$reph,$repvol, $repa),
                )
            )
            ->getForm();

        if($form->get("submit")->isDisabled() && count($voyage)==1 && count($vol)==1 && count($act)==1)
        {
            $this->addFlash("warning","eeeeeeee");

        }
        $form->handleRequest($requuest);
        if($form->isSubmitted() && $form->isValid() ){




        }
        return $this->render('cart/index.html.twig', ['items' => $voyage, 'items1' => $vol, 'items3' => $heb, 'items4' => $act
            , 'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     *
     */
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;

        } else {
            $panier[$id] = 1;
        }
        $var = 0;
        foreach ($panier as $id => $value) {
            $var += 1;
        }
        $session->set('panier', $panier);
        $cookie = new Cookie('nombre', $var);
        $res = new \Symfony\Component\HttpFoundation\Response();

        $res->headers->setCookie($cookie);
        $res->send();

        return $this->redirectToRoute('cart_panier');
    }


    /**
     * @Route("/panier/delete/{id}", name="delete_items")
     *
     */
    public function delete($id, SessionInterface $session, VoyageorganiseRepository $repv)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_panier');
    }


    /**
     * @Route("/panier/deletetab/{id}", name="delete_itemstab")
     *
     */
    public function deletetab($id, SessionInterface $session, VoyageorganiseRepository $repv)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('cart_panier');
    }


    /**
     * @Route("/panier/count", name="count")
     *
     */
    public function count(SessionInterface $session)
    {
        $panier = $session->get('panier', []);


        $size = count($panier);


        return $this->render('base_front.twig', ['size' => $size

        ]);
    }
  public function verifi1($voyage,$act,$vol)
    {
         dump($this->count($voyage));

    }

    public function check(SessionInterface $s, VoyageorganiseRepository $rep, HebergementRepository $reph, VolRepository $repvol, ActiviteRepository $repa)
    {
        $panier = $s->get('panier', []);
        $voyage[] = [];
        $vol[] = [];
        $heb[] = [];
        $act[] = [];
        foreach ($panier as $id => $value) {
            $voyage[] = ['voyage' => $rep->find($id)];
            $vol[] = ['vol' => $repvol->find($id)];
            $heb[] = ['heb' => $reph->find($id)];
            $act[] = ['act' => $repa->find($id)];
        }
        $startDays[] = [];
        foreach ($vol as $i => $value) {
            if (!empty($value["vol"])) {
                $startDays[] = $value["vol"]->getDateDepart()->format('Y-m-d');
            }
        }

        foreach ($act as $i => $value) {
            if (!empty($value["act"])) {
                $startDays[] = (new \DateTime($value["act"]->getDate()))->format('Y-m-d');
            }

        }

        foreach ($voyage as $i => $value) {
            if (!empty($value["voyage"])) {
                $startDays[] = $value["voyage"]->getDatedepart();
            }

        }
        dump($startDays);
        $uniqueArry = array();
        foreach ($startDays as $val) { //Loop1

            foreach ($uniqueArry as $uniqueValue) { //Loop2

                if ($val == $uniqueValue) {
                    continue 2; // Referring Outer loop (Loop 1)
                }
            }
            $uniqueArry[] =$val;
        }

        dump($uniqueArry);
        unset($uniqueArry[0]);
       if(empty( $uniqueArry))
       {
           return true;
       }
       else
       {
            return false;
       }
       dump($uniqueArry);

    }


}
