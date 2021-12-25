<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('main/home/index.html.twig', [
            'controller_name' => 'HomeControlerController',
        ]);
    }
    /**
     * @Route("/delete-account", name="delete_account")
     */
    public function deleteAccount(): Response
    {
        return $this->render('main/delete-account.html.twig', [
        ]);
    }
}
