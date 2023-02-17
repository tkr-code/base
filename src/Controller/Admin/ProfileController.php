<?php

namespace App\Controller\Admin;

use App\Entity\Adresse;
use App\Entity\User;
use App\Entity\Phone;
use App\Form\PhoneType;
use App\Form\ProfileAdresseType;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\ProfileChangePasswordFormType;
use App\Form\ProfileDetailsType;
use App\Repository\PhoneRepository;
use App\Service\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\UX\Cropperjs\Factory\CropperInterface;

use function PHPUnit\Framework\fileExists;

/**
 * @Route("/my-account/profile")
 */
class ProfileController extends AbstractController
{
    private $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    /**
     * @Route("/", name="profile_index", methods={"GET","POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user =  $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);


        $form2 = $this->createForm(ProfileDetailsType::class, $user);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager->flush();
            $this->addFlash('success',"Profil modifié");
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        $form3 = $this->createForm(ProfileAdresseType::class, $user);
        $form3->handleRequest($request);

        if ($form3->isSubmitted() && $form3->isValid()) {
            $adresse = $user->getAdresse();
            $adresse->setUser($user);
            $adresse->setFirstName($user->getFirstName());
            $adresse->setLastName($user->getLastName());
            $user->setAdresse($adresse);
            $entityManager->flush();

            $this->addFlash('success',"Adresse modifié");
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('admin/profile/index.html.twig', [
            'form' => $form,
            'form2' => $form2,
            'form3' => $form3,
        ]);
    }


    /**
     * @Route("/new", name="profile_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success',"success");
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/phone", name="profile_phone_index", methods={"GET"})
     */
    public function phoneIndex(PhoneRepository $phoneRepository): Response
    {        
        return $this->render('admin/profile/phone/index.html.twig', [
            'phones' =>$phoneRepository->findBy([
                        'user'=>$this->getUser()->getId(),
                    ])
        ]);
    }
    /**
     * @Route("/phone/new", name="profile_phone_new", methods={"GET","POST"})
     */
    public function phoneNew(Request $request): Response
    {
        
        $phone = new Phone();
        $phone->setUser($this->getUser());
        if($request->request->get('ajax')){
            return new JsonResponse([
                'reponse'=>false,
                'content'=>'data'
            ]);
        }else{

            $form = $this->createForm(PhoneType::class, $phone);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($phone);
                $entityManager->flush();
                $this->addFlash('success','Success');
                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('admin/profile/phone/new.html.twig', [
            'phone' => $phone,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="profile_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("-edit-password", name="profile_edit_password", methods={"GET","POST"})
     */
    public function editPassword(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface ): Response
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
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Mot de passe modifier');
            
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/profile/password/edit.html.twig', [
            'form' => $form,
        ]);
    }
    /**
     * @Route("/edit", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
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
            
            // $user->setPersonne($personne);
            $this->getDoctrine()->getManager()->flush();
            $message  = $this->translator->trans("La modification a été éffectué avec succès.");
            $this->addFlash('success',$message);
            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/profile/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{id}", name="profile_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("-image", name="profile_image", methods={"POST"}, options={"expose"= true} )
     */
    public function getImage(Request $request, Services $services, EntityManagerInterface $em)
    {
        $reponse = [
            'reponse'=>false,
            'error'=>1
        ];
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $form = $this->createForm(ProfileType::class, $user);
            $form->handleRequest($request);
            $file = $_FILES['file'];
            $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);
            $fileName = $services->aleatoire(100) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('user_images_directory'),
                $fileName
            );
            $pathImage = $this->getParameter('user_images_directory').DIRECTORY_SEPARATOR.$fileName;
            $lastImag = $user->getAvatar();
            $user->setAvatar($fileName);
            $em->flush();
            //delete img user
            if(!empty($lastImag)){

                $pathDeleteImg = $this->getParameter('user_images_directory').DIRECTORY_SEPARATOR.$lastImag;
                if(file_exists($pathDeleteImg)){
                    unlink($pathDeleteImg);
                }
            }

            
            $reponse = [
                'reponse'=>true,
                'pathImage'=>$pathImage
            ];

            return new JsonResponse($reponse);
        }
        return new JsonResponse($reponse);
    }
    /**
     * @Route("-load-profile-image-", name="get_profile_image", methods={"POST"}, options={"expose"= true} )
     */
    public function getProfilImage(Request $request)
    {
        $reponse = [
            'reponse' => false
        ];
        if($request->get('profile_img')){
            $reponse = [
                'content' => $this->render('admin/profile/_profil_img.html.twig')->getContent(),
                'reponse' => true,
            ];
        }
        return new JsonResponse($reponse);
    }

}