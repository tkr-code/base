<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(HttpClientInterface $httpClient): Response
    {
        $username = 'devAccount02';// $request->request->get('username');
        $password =$username;// $request->request->get('password');

        // Définissez l'URL de votre API Symfony pour le login
        $url = 'https://transmoney-gn.com/bpcoreM/callMFctn';

        // Effectuez la requête POST à l'API Symfony
        $response = $httpClient->request('POST', $url, [
            'json' => [
                'log' => $username,
                'pass' => $password
            ]
        ]);

        // dd($response);

        // Récupérez le code de statut de la réponse
        $status = $response->getStatusCode();

        // // Récupérez le contenu de la réponse
        $content = $response->getContent();
        dd($content);
        // // Traitez la réponse en fonction du code de statut
        // if ($status === JsonResponse::HTTP_OK) {
        //     // Succès - traitez le contenu de la réponse
        //     $responseData = json_decode($content, true);
        //     // Faites quelque chose avec les données de réponse
        //     return new JsonResponse($responseData);
        // } else {
        //     // Erreur - gérez le code de statut et le contenu de la réponse
        //     // Faites quelque chose en cas d'échec de connexion
        //     return new JsonResponse(['error' => 'Erreur de connexion'], JsonResponse::HTTP_BAD_REQUEST);
        // }
        return $this->render($this->getParameter('template').'/home/index.html.twig', [
            'controller_name' => 'HomeControlerController',
        ]);
    }
}
