<?php

namespace App\Controller\Main;

use App\Form\ContactType;
use App\Service\Email\EmailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailerInterface, EmailService $emailService): Response
    {
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('appkr@mail.com')
                ->subject('Contact depuis le site votre site')
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'theme'=>$emailService->theme(6),
                    'name'=>$contact->get('name')->getData(),
                    'mail'=>$contact->get('email')->getData(),
                    'phone'=>$contact->get('phone_number')->getData(),
                    'message'=>$contact->get('message')->getData(),
                ]);
          $mailerInterface->send($email);
          $this->addFlash('success','Votre message a été envoyé.');
        }
        return $this->renderForm('main/contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
