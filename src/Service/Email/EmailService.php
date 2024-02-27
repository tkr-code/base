<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class EmailService
{
    private $translator;
    private $globals;
    private $app_name;
    public function __construct(TranslatorInterface $translatorInterface, Environment $twig)
    {
      $this->globals = $twig->getGlobals();
      $this->app_name = $this->globals['app_name'];
      $this->translator = $translatorInterface;
    }
    public function theme($id)
    {
        $link_site = '';
        $message = '';
        $button_link = $link_site;
        $button_text = '';
        $titre = '';
        switch ($id) {
          case '1':
            // inscription verification ou desinscription
              $introduction = '';
              // $button_link = '/gestion-compte/delete-account/{token}/{id}';
              $button_link = 'app_login';
              $button_text = 'Nos catégories';
              $titre ="Confirmation email";
              $message = 'Your customer account '.$this->app_name.' was well recorded.';
            break;
          case '1.1':
            // inscription verification ou desinscription
              $introduction = '';
              // $button_link = '/gestion-compte/delete-account/{token}/{id}';
              $button_link = 'articles';
              $button_text = 'Our categories';
              $titre ="Confirmation email";
              $message = 'Your customer account '.$this->app_name.' was well recorded.';
            break;
          case '2':
            // mot de passeoublier
            $introduction = '';
            $button_link = null;
            $button_text = 'Change your password';
              $titre = 'Please change your password';
              $message = 'Were you responsible for modifying your account '.$this->app_name.' ? If so, here is the edit link.';
            break;
          case '3':
            // modifier l'email
            $introduction = '';
            $button_link = null;
            $button_text = 'Change my email';
              $titre = 'Please change email';
              $message = 'Were you at the origin of the modification of your '.$this->app_name.' account? If yes, here is the edit link .';
            break;
          case '4':
            // nouvell commande
            $introduction = '';
            $button_link = null;
            $button_text = null;
              $titre = 'invoice notice';
              $message = 'An invoice has been generated';
            break;
          case '5':
            // confirmaion user
            $introduction = '';
            $button_link ='app_login' ;
            $button_text = 'Se connecter';
              $titre = "Notice of creation of a user account";
              $message = 'A new account has been created.';
            break;
          case '5.1':
            // confirmaion user none
            $introduction = '';
            $button_link ='app_login' ;
            $button_text = 'Se connecter';
              $titre = "Notice of creation of a user account";
              $message = 'A new account has been created.';
            break;
          case '6':
            // contact
            $introduction = '';
            $button_link =null ;
            $button_text = null;
              $titre = "Visitor message";
              $message = 'A new message has been created.';
            break;
          case '7':
            // facture
            $introduction = '';
            $button_link =null ;
            $button_text = null;
              $titre = "Invoice notice";
              $message = 'Payment receipt.';
            break;
          case '8':
            // facture
            $introduction = '';
            $button_link =null ;
            $button_text = null;
              $titre = "New order";
              $message = 'An order is pending.';
            break;

          default:
            // code...
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
            'resetToken'=>'reset/NLsV4E2rKC57yaBv2Ib2VAp2n3HKzFXjFCRRULa9'
        ];
    }
}
