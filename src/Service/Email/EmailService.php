<?php

namespace App\Service\Email;

use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Crap4j;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService
{
    private $translator;
    private $csrf;
    public function __construct(TranslatorInterface $translatorInterface, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrf = $csrfTokenManager;
        $this->translator =$translatorInterface;
    }
    public function theme($id = '')
    {
        $id_user = 1;
        $cle_user = 'cleuser1';
        $cle= '';
        $site = 'Gaboma annonce';
        $introduction = 'Site de petites annonces.';
        $link_site = 'https://lesekoya.com';
        $link_img = $link_site.'/public/img/icons/favicon.png';
        $message = '';
        $link_contrat = $link_site."/public/doc/condition-d'utilisation-gaboma-annonce.pdf";
        $button_link = $link_site;
        $button_text = '';
        $titre = '';
        $link_disinscription = $link_site.'/se-desinscrire-'.$id_user.'-tkr'.$cle_user.'-gaboma-annonce';
        switch ($id) {
          case '1':
            // inscription verification ou desinscription
              $introduction = '';
              $button_link = null;
              $button_text = 'Nos catégories';
              $titre =$this->translator->trans('Hi! Please confirm your email!');
              $message = 'Votre compte leSekoya est en attente de confirmation.';
            break;
          case '2':
            // mot de passeoublier
            $introduction = '';
            $button_link = null;
            $button_text = 'Modifier votre mot de passe';
              $titre = 'Veuillez modifier votre mot de passe';
              $message = 'Étiez-vous à l’origine de la modfication de votre compte leSekoya ? Si oui, voici le lien de modification.';
            break;
          case '3':
            // modifier l'email
            $introduction = '';
            $button_link = null;
            $button_text = 'Modifier votre e-mail';
              $titre = 'Veuillez modifier e-mail';
              $message = 'Étiez-vous à l’origine de la modfication de votre compte gaboma annonce ? Si oui, voici le lien de modification.';
            break;
          case '4':
            // nouvell commande
            $introduction = '';
            $button_link = null;
            $button_text = null;
              $titre = 'Avis de facture';
              $message = 'Une facture à été générée';
            break;
          case '5':
            // confirmaion user
            $introduction = '';
            $button_link ='app_login' ;
            $button_text = 'Se connecter';
              $titre = "Avis de création d'un compte utilisateur";
              $message = 'Un nouveau compte a été crée.';
            break;
          case '6':
            // contact
            $introduction = '';
            $button_link =null ;
            $button_text = null;
              $titre = "Message visiteur";
              $message = 'Un nouveau message a été crée.';
            break;
          case '7':
            // Se désinscrire
            $introduction = '';
            $button_link ='delete_account' ;
            $button_text = 'Se désinscrire';
              $titre = "Avis de désinscription";
              $message = "Vous ne recevrez plus d'email a partir de maintenant";
            break;
          case '8':
            // modifier email
            $introduction = '';
            $button_link ='edit_email' ;
            $button_text = "Modifier l'email";
            $titre = "Avis de modification d'email";
              $message = "Vous allez modifier votre email";
            break;

          default:
            // demo
            $introduction = '';
            $button_link =null ;
            $button_text = null;
              $titre = "Message visiteur (demo)";
              $message = 'Un nouveau message a été crée.(demo)';
            break;
        }
        return 
        [
            'name'=>$titre,
            'message'=>$message,
            'btn'=>[
                'path'=>$button_link,
                'text'=>$button_text
            ],
            'tokenKey'=>$this->csrf->getToken($button_link)
        ];
    }
}
