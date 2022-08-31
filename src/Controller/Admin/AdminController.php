<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    private $translator;
    public function __construct(TranslatorInterface $translatorInterface)
    {
        $this->translator = $translatorInterface;
    }
    /**
     * @Route("/my-account/", name="admin")
     */
    public function index(UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/dashboard/index.html.twig',[
            'parent_page'=>$this->translator->trans('Dashboard')
        ]);
    }
}
