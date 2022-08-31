<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Gere la navigation par order de privillege
 */
class NavExtension extends AbstractExtension
{
    private $translator;
    private $twig;
    public function __construct(TranslatorInterface $translator, Environment $twig)
    {
        $this->translator = $translator;
        $this->twig = $twig;
    }
    public function getFunctions():array
    {
        return[
            new TwigFunction('sidebar',[$this,'getNavs'])
        ];
    }
    
    public function getNavs()
    {
        $globals = $this->twig->getGlobals();
        
        return 
        [
            //navigation vu par tous les users
            'navs'=>
            [
                [
                    'name'=>$this->translator->trans('Dashboard'),
                    'icon'=>'fas fa-tachometer-alt',
                    'links'=>[
                        [
                            'name'=>$this->translator->trans('Dashboard').' 1',
                            'path'=>'admin'
                        ]
                    ]
                ],
                [
                    'id'=>'profile',
                    'name'=>'Profile',
                    'icon'=>'fas fa-user',
                    'path'=>'profile_index',
                ],
                // [
                //     'name'=>'Chat',
                //     'icon'=>'fas fa-envelope',
                //     'path'=>'admin_chat_index',
                // ],
                [
                    'name'=>$globals['app_name'],
                    'icon'=>'fa fa-home',
                    'links'=>
                        [
                            [
                                'name'=>$this->translator->trans('Home'),
                                'path'=>'home'
                            ]
                        ]
                ],
            ],
            //Navigation vu par l'administrateur
            'admin'=>
            [
                [
                    'name'=>$this->translator->trans('User'),
                    'icon'=>'fas fa-users',
                    'links'=>[
                        [
                            'name'=>$this->translator->trans('Users'),
                            'path'=>'user_index',
                        ],
                        [
                            'name'=>$this->translator->trans('New'),
                            'path'=>'user_new',
                        ],
                    ]
                ]
            ],
            //Navigation vu par l'editeur
            'editor'=>[
                // [
                //     'name'=>'Test',
                //     'path'=>'home'
                // ]
            ]
        ];
    }
}