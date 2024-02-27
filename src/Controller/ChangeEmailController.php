<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeEmailRequestFormType;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Security\Core\Exception\EmailExistsException;
#[Route('/change-email')]
class ChangeEmailController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/{token}', name: 'app_change_email_check_token')]
    public function request(string $token, UserRepository $userRepository, Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        $user = $userRepository->findOneBy([
            'token' => $token
        ]);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(ChangeEmailRequestFormType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Vérifier si l'email existe déjà dans la base de données
            // Modifier l'email de l'utilisateur
            // $user->setEmail($newEmail);
            $existingUser = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
            if ($existingUser) {
               $this->addFlash('error','Email: '.$form->get('email')->getData().' est déjà utilisé.');
               return $this->render('email/change_email.html.twig', [
                'requestForm' => $form,
            ]);
            }
            
            // Effacer le token après la modification de l'email
            $user->setToken(null);

            // Enregistrer les modifications dans la base de données
            $this->entityManager->flush();
            $this->addFlash('success','Votre email a été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('email/change_email.html.twig', [
            'requestForm' => $form,
        ]);
    }
}
