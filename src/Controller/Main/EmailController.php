<?php

namespace App\Controller\Main;

use App\Entity\Order;
use App\Repository\OptionsRepository;
use App\Service\Email\EmailService;
use App\Service\Order\OrderService;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

class EmailController extends AbstractController
{
    private $emailService;
    private $mailer;
    private $service;
    public function __construct(Service $service, EmailService $emailService, MailerInterface $mailerInterface )
    {
        $this->emailService = $emailService;
        $this->service = $service;
        $this->mailer = $mailerInterface;

    }
    
    #[Route("/send/edit-email", name:"send_edit_email", methods:["GET","POST"])]
    public function editEmailResponse(Request $request, EntityManagerInterface $entityManager, Environment $environment, OptionsRepository $optionsRepository):Response
    {
        $app_url = $optionsRepository->findOneBy([
            'name'=>'app_url'
        ]);
        $email_contact = $optionsRepository->findOneBy([
            'name'=>'email_contact'
        ]);
        $twig = $environment->getGlobals();
        
        $user = $this->getUser();
        // Générer un token unique pour l'utilisateur
        $token = $this->service->aleatoire(200);

        $user->setToken($token);
        $email = (new TemplatedEmail())
            ->from(new Address($email_contact->getValue(),'site:'. $app_url->getValue()))
            ->to($user->getEmail())
            ->subject("Edit email")
            ->htmlTemplate('email/reset-email.html.twig')
            ->context([
                'user'=>$this->getUser(),
                'token'=>$token,
                'theme'=> $this->emailService->theme(3),
            ]);
        try
        {
            $this->mailer->send($email);

            $entityManager->flush($user);
            return new JsonResponse('success',200);
        } catch (TransportExceptionInterface $e) 
        {
            if($e->getMessage()){
                return new JsonResponse('error',200);
            }   
        }

    }
    
    #[Route("gestion-compte/delete-account/{token}/{id}", name:"delete_account")]
    public function deleteAccount($token, $id): Response
    {
        if (!$this->isCsrfTokenValid('delete_account', $token)) {
            throw new AccessDeniedException('Acces denied');
        }
        
        return $this->render('main/delete-account.html.twig', [
            'id'=>$id
        ]);
    }
    
    
    #[Route("/send/delete-account", name:"send_delete_account", methods:["GET","POST"] )]
    public function deleteAccountResponse(Request $request):Response
    {
        $user = $this->getUser();
        $email = (new TemplatedEmail())
            ->from(new Address('contact@lest.sn', 'lest.sn'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('email/delete-account.html.twig')
            ->context([
                'user'=>$user,
                'theme'=> $this->emailService->theme(7),
            ]);
        try
        {
            $this->mailer->send($email);
            return new JsonResponse('success',200);
        } catch (TransportExceptionInterface $e) 
        {
            if($e->getMessage()){
                return new JsonResponse('error',$e->getCode());
            }   
        }

    }
}
