<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ActiviteRepository;
use App\Repository\HebergementRepository;
use App\Repository\ReservationRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageorganiseRepository;
use phpDocumentor\Reflection\Types\Null_;
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
        $res = new \Symfony\Component\HttpFoundation\Response();
        $nb=count($panier);
            dump($voyage);

        $cookie = new Cookie("nombre",$nb);

        $res->headers->setCookie($cookie);
        $res->send();
        $form = $this->createFormBuilder($var)
            ->add('nbrPlace', NumberType::class)
            ->add('submit', SubmitType::class, array(
                    'label' => 'Submit',
                    'disabled' => ! ($this->check($s,$rep,$reph,$repvol, $repa) && $this->check2($s,$rep,$repvol)),
                )
            )
            ->getForm();

        if( ! $form->get("submit")->isDisabled() && count($voyage)==1 && count($vol)==1 && count($act)==1)
        {
            $this->addFlash("warning","Vous avez selectionne 3 elements dans le meme jour  vous pouvez passer  ");

        }
        $form->handleRequest($requuest);
        if($form->isSubmitted() && $form->isValid() ){


            return $this->redirectToRoute('reservation_group', array('idvol' => $vol[1]["vol"]->getIdVol(),'idact'=>$act[1]["act"]->getRefact(),'idvoy'=>$voyage[1]["voyage"]->getIdvoy(),'quantite'=>$form["nbrPlace"]->getData()));




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
        unset($panier[0]);
        $var=count($panier);

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



    public function check(SessionInterface $s, VoyageorganiseRepository $rep, HebergementRepository $reph, VolRepository $repvol, ActiviteRepository $repa)
    {$panier = $s->get('panier', []);
        $voyage[] = [];$vol[] = [];$heb[] = [];$act[] = [];

        if(empty($panier)==false)
        {
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
        $messagesFiltered = [];
        $val=$startDays[1];
        foreach ($startDays as $message) {
            if ( $val != $message) {
                $messagesFiltered[] = $message;
            }
        }
        $startDays = $messagesFiltered;

        unset($startDays[0]);


            if(empty($startDays)){

                return true;
            }
            else
            {
                return false;
            }}
        return false;



    }

    public function check2(SessionInterface $s, VoyageorganiseRepository $rep, VolRepository $repvol)
    {$panier = $s->get('panier', []);
        $voyage[] = [];
        $vol[] = [];
        $heb[] = [];
        $act[] = [];

        if(empty($panier)==false)
        {
            foreach ($panier as $id => $value) {
                $voyage[] = ['voyage' => $rep->find($id)];
                $vol[] = ['vol' => $repvol->find($id)];
            }
            $startDays[] = [];
            foreach ($vol as $i => $value) {
                if (!empty($value["vol"])) {
                    $startDays[] = $value["vol"]->getDateArrivee()->format('Y-m-d');
                }
            }

            foreach ($voyage as $i => $value) {
                if (!empty($value["voyage"])) {
                    $startDays[] = $value["voyage"]->getDatearrive();
                }
            }
            $messagesFiltered = [];

            if(!empty($startDays )) {
                $val = $startDays[1];
                foreach ($startDays as $message) {
                    if ($val != $message) {
                        $messagesFiltered[] = $message;
                    }
                }
                $startDays = $messagesFiltered;

                unset($startDays[0]);



                if (empty($startDays)) {

                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;


    }


}
