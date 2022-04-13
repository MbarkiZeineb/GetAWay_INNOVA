<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\HebergementRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageorganiseRepository;
use http\Env\Request;
use http\Env\Response;
use mysql_xdevapi\Table;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Voyageorganise;
class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_panier")
     */
    public function index(SessionInterface  $s,VoyageorganiseRepository $rep,HebergementRepository $reph,VolRepository  $repvol ,ActiviteRepository  $repa) :\Symfony\Component\HttpFoundation\Response
    {
        $panier=$s->get('panier',[]);
        $voyage[]=[];
        $vol[]=[];
        $heb[]=[];
        $act[]=[];
$var=0;
          foreach ($panier as $id=>$value) {
              $var+=1;
              $voyage[] = ['voyage' => $rep->find($id)];
              $vol[] = ['vol' => $repvol->find($id)];
              $heb[] = ['heb' => $reph->find($id)];
              $act[]=['act' => $repa->find($id)];
          }
           $cookie=new Cookie('nombre',$var);
        $res = new \Symfony\Component\HttpFoundation\Response();
        $res->headers->setCookie( $cookie );
        $res->send();

        // Disponible uniquement dans le protocole HTTP

        return $this->render('cart/index.html.twig', ['items'=>$voyage,'items1'=>$vol,'items3'=>$heb,'items4'=>$act
        ,'size'=>$var
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

        $session->set('panier', $panier);



        return $this->redirectToRoute('cart_panier');
    }


    /**
     * @Route("/panier/delete/{id}", name="delete_items")
     *
     */
    public function delete($id, SessionInterface $session,VoyageorganiseRepository $repv)
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
    public function deletetab($id, SessionInterface $session,VoyageorganiseRepository $repv)
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
    public function count( SessionInterface $session)
    {
        $panier = $session->get('panier', []);


        $size = count($panier);


        return $this->render('base_front.twig', ['size'=>$size

        ]);
    }



}
