<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("delete/")
 */
class DeleteController extends AbstractController
{
    private $parent_page;
    public function __construct(TranslatorInterface $translatorInterface)
    {
        $this->parent_page = $translatorInterface->trans('User');
    }
    
    /**
     * @Route("account-delete/{id}", name="delete_account_user", methods={"POST"})
     */
    public function delete(Request $request, User $user, SessionInterface $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->get('security.token_storage')->setToken(null); // force la deconnect manuellement
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $session->invalidate(0); 
            $this->addFlash('success','Votre compte a été supprimé');
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}