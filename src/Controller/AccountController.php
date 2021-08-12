<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\UpdatePass;
use App\Form\EditUserType;
use App\Form\NameSearchType;
use App\Form\UpdatePassType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login(UserRepository $userRepo,AuthenticationUtils $utils): Response
    {
        $users= $userRepo->findAll();
        if($users==null){
            $addUser = new User();
            $addUser->setPseudo('ADMIN');
            $addUser->setPassword('$2y$10$/f.vX80MTY6J6zEFhnciWu7KHteUb3OzpF/0XeuAdhvCJd9a2/jAy');
            $addUser->setRoles(['ROLE_ADMIN']);
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($addUser); 
            $manager->flush();
        }
        $error = $utils->getLastAuthenticationError();
        $userName = $utils->getLastUsername();
        if($this->getUser())
        {
            return $this-> redirectToRoute('home_page');
        }
        return $this->render('account/login.html.twig', [
            'error' => $error !== null,
            'userName' => $userName,
        ]);
    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/utilisateur", name="user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepo,Request $request): Response
    {
        $users= $userRepo->findBy(array(), array('id' => 'DESC'));
        $formName = $this->createForm(NameSearchType::class);
        $formName-> handleRequest($request);
        if($formName->isSubmitted() && $formName->isValid())
        {
            $name = $formName->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM user u
            WHERE u.pseudo LIKE \'%'.$name.'%\''.' ORDER BY u.id DESC';
            $result=$manager->prepare($sql);
            $users=$result->executeQuery();

        }
        return $this->render('account/index.html.twig', [
            'users'=>$users,
            'formName'=> $formName->createView(),
        ]);
    }

    /**
     * permet d'ajouter un utilisateur 
     * @Route("/ajouter-utilisateur", name="addUser")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function addUser(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $addUser = new User();
        $addUserForm = $this->createForm(UserType::class,$addUser);
        $addUserForm-> handleRequest($request);
        if($addUserForm->isSubmitted() && $addUserForm->isValid() && empty($addUserForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $pass = $encoder->encodePassword($addUser, $addUser->getPassword());
           
            $addUser->setPassword($pass);
            $manager->persist($addUser); 
            $manager->flush();
            return $this-> redirectToRoute('user');
        }   
        return $this->render('account/addUser.html.twig', [
            'addUserForm'=> $addUserForm->createView(),
        ]);
    }

    /**
     * permet de modifier un utilisateur 
     * @Route("/modifier-utilisateur/{slug}", name="editUser")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function editUser($slug,UserRepository $userRepo,Request $request)
    {
        $editUser = $userRepo->findOneBySlug($slug);
        $editUserForm = $this->createForm(EditUserType::class,$editUser);
        $editUserForm-> handleRequest($request);
        if($editUserForm->isSubmitted() && $editUserForm->isValid() && empty($editUserForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($editUser); 
            $manager->flush();
            return $this-> redirectToRoute('user');
        }  
        return $this->render('account/editUser.html.twig', [
            'editUser' => $editUser,
            'editUserForm'=> $editUserForm->createView(),
        ]);
    }

    /**
     * permet de modifier le mot de passe utilisateur 
     * @Route("/modifier-mot-de-passe/{slug}", name="updatePass")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function updatePass($slug,UserRepository $userRepo,Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user= $userRepo->findOneBySlug($slug);
        $updatePass= new UpdatePass();
        $updatePassForm = $this->createForm(UpdatePassType::class,$updatePass);
        $updatePassForm-> handleRequest($request);
        if($updatePassForm->isSubmitted() && $updatePassForm->isValid())
        {
            if (!password_verify($updatePass->getOldPass(), $user->getPassword()))
            {
                $updatePassForm->get('oldPass')->addError(new FormError("votre ancien mot de passe est incorrect")); 
            }else
            {
                $newPass= $updatePass->getNewPass();
                $pass= $encoder->encodePassword($user, $newPass);
                $user->setPassword($pass);
                $manager=$this->getDoctrine()->getManager();
                $manager->persist($user); 
                $manager->flush();
                return $this-> redirectToRoute('user');
            }
            
        }   
        return $this->render('account/updatePass.html.twig', [
            'updatePassForm'=> $updatePassForm->createView(),
        ]);
    }

    /**
     * permet de supprimer un utilisateur
     * @Route("/supprimer-utilisatuer/{slug} ", name="removeUser")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function removeUser($slug,UserRepository $userJobRepo)
    {   
        $removeUser = $userJobRepo->findOneBySlug($slug);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeUser); 
        $manager->flush();
        return $this-> redirectToRoute('user');
    }
}
