<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/update/user/email", name="main_user")
     */
    public function index(): Response
    {
        return $this->render('main/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
