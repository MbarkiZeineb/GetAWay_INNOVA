<?php

namespace App\Controller;

use App\Repository\HebergementRepository;
use App\Repository\VolRepository;
use App\Repository\VoyageorganiseRepository;
use http\Env\Request;
use mysql_xdevapi\Table;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Voyageorganise;
class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_panier")
     */
    public function index(SessionInterface  $s,VoyageorganiseRepository $rep,HebergementRepository $reph,VolRepository  $repvol )
    {
        $panier=$s->get('panier',[]);
        dump($panier);

          foreach ($panier as $id=>$value) {
              $voyage[] = ['voyage' => $rep->find($id)];
              $vol[] = ['vol' => $repvol->find($id)];
              $heb[] = ['heb' => $reph->find($id)];
          }
         dump($voyage);
        dump($vol);




        return $this->render('cart/index.html.twig', ['items'=>$voyage,'items1'=>$vol,'items3'=>$heb

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
    public function delete($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            dump($id);
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_panier');
    }

}
