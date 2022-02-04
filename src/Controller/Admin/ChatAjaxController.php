<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatAjaxController extends AbstractController
{
    /**
     * @Route("/chat/new", name="chat_new")
     */
    public function chatNew(MessageRepository $messageRepository, UserRepository $userRepository, Request $request): Response
    {
        if (isset($_POST['chat_new'])) {
            $id_recepteur =(int) $_POST['id_recepteur'];
            $postMessage = $_POST['message'];
            $recepteur = $userRepository->find($id_recepteur);
            $message = new Message();
            $message->setEmetteur($this->getUser());
            $message->setRecepteur($recepteur);
            $message->setMessage($postMessage);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return new JsonResponse('success');
        }
        return new JsonResponse('Erreur');
    }

    /**
     * @Route("/chat/get", name="chat_get_all", methods={"GET","POST"})
     */
    public function getChat(Request $request, MessageRepository $messageRepository): Response
    {
        $id_emetteur = $_POST['id_emetteur'];
        $id_recepteur = $_POST['id_recepteur'];
        return $this->render('chat/get-chat.html.twig', [
            'messages' => $messageRepository->conversation($id_emetteur, $id_recepteur),
            'emetteur' => $id_emetteur
        ]);
    }

    /**
     * @Route("/chat/get-content/", name="chat_get_content", methods={"GET","POST"})
     */
    public function getChatContent(Request $request, UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        $user = new User();
        $recepteur =(int) $_POST['recepteur'];
        if(isset($_POST['content'])){
            $user = $userRepository->find($recepteur);
        }
        return $this->render('chat/content.html.twig', [
            'user'=>$user,
            'messages'=>$messageRepository->conversation(19,20),
            'id_emetteur'=>$recepteur
        ]);
    }

    /**
     * @Route("/chat/get-user-conversation/", name="chat_get_user_conversation", methods={"GET","POST"})
     */
    public function getChatUser(Request $request,MessageRepository $messageRepository): Response
    {
        
        return $this->render('chat/user-content.html.twig', [
            'messages'=>$messageRepository->conversationUser(19,20),
        ]);
    }

    /**
     * @Route("/chat/", name="chat_send", methods={"GET","POST"})
     */
    public function editEmailResponse(Request $request): Response
    {

        return new JsonResponse('error', 200);
    }
}
