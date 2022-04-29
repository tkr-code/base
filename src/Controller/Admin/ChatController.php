<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends AbstractController
{
    /**
     * @Route("/admin/chat", name="admin_chat_index")
     */
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        // dd(
        //   $userRepository->chatUsers(37)
        // );
        return $this->render('admin/chat/index1.html.twig', [
            'users' =>$userRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/chat/new", name="admin_chat_new")
     */
    public function chatNew(MessageRepository $messageRepository, Request $request ): Response
    {
        $message = new Message();
        $message->setEmetteur($this->getUser());
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success','Le message a été envoyé');
            return $this->redirectToRoute('admin_chat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('admin/chat/new.html.twig', [
            'form' =>$form
        ]);
    }
}
