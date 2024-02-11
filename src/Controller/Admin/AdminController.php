<?php

namespace App\Controller\Admin;

use App\Entity\Phone;
use App\Form\PhoneType;
use App\Form\ProfileChangePasswordFormType;
use App\Form\ProfileType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/my-account')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'parent_page'=>'Tableau de bord',
        ]);
    }
        
    #[Route('/profile', name: 'profile_index', methods: ['GET','POST'],  host: 'localhost', schemes: ['http', 'https'] )]
    public function profile(Request $request)
    {
        $user =  $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        return $this->render("admin/profile/index.html.twig",[
            'id'=>$request->attributes->get('id'),
            'form'=>$form
        ]);
    }


    #[Route("/phone/new", name:"profile_phone_new", methods:["GET","POST"])]
    public function phoneNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $phone = new Phone();
        $phone->setUser($this->getUser());
        $form = $this->createForm(PhoneType::class, $phone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($phone);
            $entityManager->flush();
            $this->addFlash('success','Success');
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/profile/phone/new.html.twig', [
            'phone' => $phone,
            'form' => $form,
        ]);
    }

    
    #[Route("/profile/edit", name:"profile_edit", methods:["GET","POST"])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {   $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user,[]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('avatar')->getData();
            $lastImag = $user->getAvatar();
            if ($image) {
                $fichier = md5(uniqid()). '.'.$image->guessExtension();
                
                $image->move($this->getParameter('user_images_directory'),$fichier);

                $user->setAvatar($fichier);
                //delete img user
                if(!empty($lastImag)){

                    $pathDeleteImg = $this->getParameter('user_images_directory').DIRECTORY_SEPARATOR.$lastImag;
                    if(file_exists($pathDeleteImg)){
                        unlink($pathDeleteImg);
                    }
                }
            }
            $entityManager->flush();
            $this->addFlash('success',"La modification a été éffectué avec succès.");
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/profile/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route("/profile-edit-password", name:"profile_edit_password", methods:["GET","POST"])]
    public function editPassword(Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasherInterface ): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        $form = $this->createForm(ProfileChangePasswordFormType::class);        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasherInterface->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $entityManager->flush();
            $this->addFlash('success','Mot de passe modifier');
            
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/profile/password/edit.html.twig', [
            'form' => $form,
        ]);
    }

}
