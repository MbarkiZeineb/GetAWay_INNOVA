<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\ReclamationType;
use App\Form\ResetPassType;
use App\Form\UserbackType;
use App\Form\UserType;
use App\Repository\AvionRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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
        dump($this->getUser()->getSalt());
        if($this->getUser()->eraseCredentials()==0)
            return $this->redirectToRoute('security_login');
        else if($this->getUser()->getSalt()=="Client" || $this->getUser()->getSalt()=="Offreur" )

            return $this->render('user/index1.html.twig');
        else

            return $this->redirectToRoute('app_user_index');

    }


    /**
     * @Route("/profil", name="app_user_pro", methods={"GET"})
     */
    public function profilCO(EntityManagerInterface $entityManager): Response
    {

        return $this->render('user/index1.html.twig');

    }


    /**
     * @Route("/afficheAvion/{ida}", name="app_avion", methods={"GET"})
     */
    public function listByidagent(UserRepository $userRepository, AvionRepository $avionRepository,$ida): Response
    {
        $user=$userRepository->find($ida);
        $avion=$avionRepository->listByida($ida);
        dump($ida);
        return $this->render('avion/index.html.twig', [
            'user'=>$user,
            'avions' => $avion,
        ]);
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

            return $this->redirectToRoute('app_user_pro', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/editfront.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
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
     * @return void
     * @Route("/getrecbyidc/{idc}",name="getrecbyidc")
     */
    public function allRec(UserRepository $userRepository,NormalizerInterface $Normalizer,$idc)
    {
        $user=$userRepository->find($idc);
        $reclamation=$this->getDoctrine()->getManager()->getRepository(Reclamation::class)->listReclamationByidc($user->getId());
        $jsonContent=$Normalizer->normalize($reclamation,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
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



    /**
     * @Route("/activer/{id}", name="activer", methods={"GET"})
     */
    public function activer( EntityManagerInterface $entityManager,$id,FlashyNotifier $flashy)
    {
        $user=$entityManager->getRepository(User::class)->find($id);
        $user->setEtat(1);
        $entityManager->flush();
        $flashy->success('Event created!', 'http://your-awesome-link.com');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

    }


    /**
     * @Route("/bloquer/{id}", name="bloquer", methods={"GET"})
     */
    public function bloquer( EntityManagerInterface $entityManager,$id)
    {
        $user=$entityManager->getRepository(User::class)->find($id);
        $user->setEtat(0);
        $entityManager->flush();
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route ("/listDql",name="triuser")
     */

    function OrderByMailDql(UserRepository $userRepository)
    {
        $users=$userRepository->OrderBymail();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);

    }




    /**
     * @Route("/mdpoub", name="mdpoub")
     */
    public function mdpoub(Request $request, UserRepository $users, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, FlashyNotifier $flashy
    ): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneByEmail($donnees['email']);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('security_login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('security_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new Email())
                ->from('omayma.djebali@esprit.tn')
                ->to($user->getEmail())
                ->subject('Demande de reinitialisation de mot de passe !')
                ->text('Sending emails is fun again!')
                ->html("Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site GetAway. Veuillez cliquer sur le lien suivant : . $url" );
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $flashy->success('Event created!');

            // On redirige vers la page de login
            return $this->redirectToRoute('security_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('user/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, FlashyNotifier $flashy)
    {
        // On cherche un utilisateur avec le token donné
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken'=> $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('security_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $flashy->success('Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('security_login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('user/reset_password.html.twig', ['token' => $token]);
        }

    }





}
