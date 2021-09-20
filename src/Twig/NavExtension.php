<?php 
namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    const icon ='far fa-circle';
    private $urlGenerator;
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function getFunctions():array
    {
        return[
            new TwigFunction('sidebar',[$this,'getNavs'])
        ];
    }

    public function getNavs()
    {
        return 
        [
            'app'=>
            [

            ],
            'admin'=>
            [
                // [
                //     'title'=>'user',
                //     'path'=>$this->urlGenerator->generate('user_index'),
                //     'icon'=>'fas fa-users'
                // ]
            ],
            'dashboard'=>
            [

            ],
            'editor'=>
            [
                // [
                //     'title'=>'customer',
                //     'path'=>$this->urlGenerator->generate('admin_client_index'),
                //     'icon'=>'far fa-circle'
                // ]
            ]
        ];
    }
}