<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    private $translator;
    private $app_name;
    public function __construct(Environment $twig, TranslatorInterface $translator)
    {
        $this->app_name = 'Base';
        $this->translator = $translator;
    }
    const icon = 'far fa-circle';
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar', [$this, 'getNavs'])
        ];
    }

    public function getNavs()
    {
        return
            [
                'navs' =>
                [
                    [
                        'name' => $this->translator->trans('Dashboard'),
                        'icon' => 'fas fa-tachometer-alt',
                        'links' => [
                            [
                                'name' => $this->translator->trans('Dashboard') . ' 1',
                                'path' => 'admin'
                            ]
                        ]
                    ],
                    // [
                    //     'id' => 'profile',
                    //     'name' => 'Profile',
                    //     'icon' => 'fas fa-home',
                    //     'path' => 'profile_index',
                    // ],
                    [
                        'name' => $this->app_name,
                        'icon' => 'fa fa-home',
                        'links' =>
                        [
                            [
                                'name' => $this->translator->trans('Home'),
                                'path' => 'home'
                            ],
                        
                        ]
                    ],
                    
                    
                ],
                'admin' =>
                [
                        // [
                    //     'name' => 'Gérant',
                    //     'icon' => 'fas fa-users',
                    //     'links' => [
                    //         [
                    //             'name' => 'Gérants',
                    //             'path' => 'gerant_index',
                    //         ],
                    //         [
                    //             'name' => 'Nouveau',
                    //             'path' => 'gerant_new',
                    //         ],
                    //     ]

                    // ],

                    [
                        'name' => 'Utilisateurs',
                        'icon' => 'fas fa-users',
                        'links' => [
                            [
                                'name' => 'Liste',
                                'path' => 'app_admin_user_index',
                            ],
                            [
                                'name' => 'Nouveau',
                                'path' => 'app_admin_user_new',
                            ],
                        ]

                    ],

                ],
                'editor' =>
                []
            ];
    }
}
