<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;


class GetUser extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public function Get_User() :User
    {   $user=new User();
        if($this->security->getUser())
        {

            $id = $this->security->getUser()->getUsername();

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($id);

        }
        return $user;
    }


}