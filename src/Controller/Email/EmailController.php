<?php

namespace App\Controller\Email;

use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\Email\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    private $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    /**
     * @Route("/email", name="email_index")
     */
    public function index(): Response
    {
        $paths = [
            [
                'path'=>'email_demo',
                'name'=>'demo'
            ],
            [
             'path'=>'email_register',
             'name'=>'Inscription'
            ],
            [
                'path'=>'email_reset_password',
                'name'=>'Mot de passe oublié'
            ],
            [
                'path'=>'email_new_user',
                'name'=>'Nouvelle utulisateur'
            ],
            [
                'path'=>'email_reset_email',
                'name'=>"Modifier l'email"
            ],
            [
                'path'=>'email_delete_account',
                'name'=>'Supprimer un compte'
            ],
        ];
        return $this->render('email/index.html.twig', [
            'paths' =>$paths 
        ]);
    }
    /**
     * @Route("/email/demo", name="email_demo")
     */
    public function demo(): Response
    {
        return $this->render('email/demo.html.twig', [
            'theme'=>$this->emailService->theme()
        ]);
    }
    /**
     * @Route("/email/contact", name="email_contact")
     */
    public function contact(): Response
    {
        return $this->render('email/contact.html.twig', [
            'theme'=>$this->emailService->theme(6),
            'name'=>'Malick tounkara',
            'phone'=>'781278288',
            'mail'=>'malick@gmail.com',
            'message'=>'Vous faites des logicies ?'
        ]);
    }
    /**
     * @Route("/email/new-user", name="email_new_user")
     */
    public function newUser(UserRepository $userRepository): Response
    {
        $user = $userRepository->find(103);
        return $this->render('email/new-user.html.twig',[
            'theme'=>$this->emailService->theme(5),
            'user'=>$user,
            'password'=>'password' 
        ]);
    }
    /**
     * @Route("/email/confirmation", name="email_register")
     */
    public function confirmation(EmailService $emailService): Response
    {
        return $this->render('email/confirmation.html.twig',
        [
            'theme'=>$emailService->theme(1)
            ]
        );
    }
    /**
     * @Route("/email/reset-password", name="email_reset_password")
     */
    public function reserPassword(EmailService $emailService): Response
    {
        return $this->render('email/reset-password.html.twig',[
            'theme'=>$emailService->theme(2)
        ]);
    }
    /**
     * @Route("/email/reset-email", name="email_reset_email")
     */
    public function resetEmail(EmailService $emailService): Response
    {
        return $this->render('email/reset-email.html.twig',[
            'theme'=>$emailService->theme(8)
        ]);
    }
    /**
     * @Route("/email/delete-account", name="email_delete_account")
     */
    public function deleteAccount(EmailService $emailService): Response
    {
        return $this->render('email/delete-account.html.twig',[
            'theme'=>$emailService->theme(7),
            'btnColor'=>'#d33'
        ]);
    }
}
