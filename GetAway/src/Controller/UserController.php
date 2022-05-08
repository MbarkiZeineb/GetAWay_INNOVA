<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Form\UserbackType;
use App\Form\UserType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/pro", name="app_user_profil", methods={"GET"})
     */
    public function profil(EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/index1.html.twig');
    }





    /**
     * @Route("/d/{id}", name="delete", methods={"GET"})
     */
    public function delete1(EntityManagerInterface $entityManager,$id): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->find($id);
        $entityManager->remove($users);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }



    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();


        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newsecurity", name="app_user_newsecurity", methods={"GET", "POST"})
     */
    public function newsecurity(Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('security_login');

        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return void
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {

        return $this->render('user/login.html.twig');

    }

    /**
     * @return void
     * @Route("/deconnexion",name="security_logout")
     */
    public function logout()
    {
    }




    /**
     * @Route("/newback", name="app_user_newback", methods={"GET", "POST"})
     */
    public function new1(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserbackType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index');
        }
        return $this->render('user/newback.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

   /* /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    //public function show(User $user): Response
    //{
      //  return $this->render('user/show.html.twig', [
           // 'user' => $user,
        //]);
    //}

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserbackType::class, $user);
        $form->handleRequest($request);
        dump($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editfront", name="app_user_editfront", methods={"GET", "POST"})
     */
    public function editfront(Request $request, User $user, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/editfront.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/affichrecfront/{idc}", name="app_reclamationfront", methods={"GET"})
     */
    public function listReclamationByidc(UserRepository $userRepository,ReclamationRepository $recRepository,$idc): Response
    {
        $user=$userRepository->find($idc);
        $reclamation=$recRepository->listReclamationByidc($user->getId());
        return $this->render('user/indexfront.html.twig', [
            'user'=>$user,
            'reclamations' => $reclamation,
        ]);
    }


    /**
     * @param Request $request
     * @return void
     * @Route("/inscription",name="inscri")
     */
    public function inscription(Request $request,UserPasswordEncoderInterface $encoder )
    {
        $email=$request->query->get("email");
        $nom=$request->query->get("nom");
        $prenom=$request->query->get("prenom");
        $password=$request->query->get("password");
        $role=$request->query->get("role");
        $numtel=$request->query->get("numtel");
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            return new Response("email invalide");
        }
        $user=new User();
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPrenom($prenom);
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setRole($role);
        $user->setNumtel($numtel);
        try {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("compte cree",200);

        }catch(\Exception $ex){
            return new Response("exception".$ex->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return void
     * @Route ("/signin",name="signin")
     */
    public function signin(Request $request)
    {
        $email=$request->query->get("email");
        $password=$request->query->get("password");
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->findOneBy(['email'=>$email]);
        if($user)
        {
            if(password_verify($password,$user->getPassword()))
            {
                $encoder=new JsonEncoder();
                $normalizer=new ObjectNormalizer();
                $normalizer->setCircularReferenceHandler(function ($object){
                    return $object;
                });
                $serializer=new Serializer([$normalizer],[$encoder]);
                $formatted =$serializer->normalize($user);
                return new JsonResponse($formatted);

            }
            else{
                return new Response("password not found");

            }
        }
        else {
            return new Response("user not found ");
        }
    }


    /**
     * @param Request $request
     * @return void
     * @Route ("/editUser",name="editUser")
     */
    public function editUser(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $id=$request->get("id");
        $nom=$request->query->get("nom");
        $prenom=$request->query->get("prenom");
        $email=$request->query->get("email");
        $password=$request->query->get("password");
        $numtel=$request->query->get("numtel");
        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPrenom($prenom);
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setNumtel($numtel);

        try {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("success",200);

        }catch(\Exception $ex){
            return new Response("fail".$ex->getMessage());
        }
    }
    /**
     * @Route("/getPasswordByEmail",name="app_password")
     */
    public function getPasswordByEmail(Request $request)
    {
        $email=$request->get('email');
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email'=>$email]);
        if($user){
            $password = $user->getPassword();
            $serializer =new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");
    }


}