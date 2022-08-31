<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('date_format_fr', [$this, 'doSomething']),
            new TwigFilter('phone_format', [$this, 'phoneFormat']),

        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dateFilter', [$this, 'doSomething']),
        ];
    }
    public function phoneFormat(string $phone){
        if (strlen($phone) == 9) {
            return substr($phone, 0, 2).' '.substr($phone, 2, 3).' '.substr($phone, 5, 2).' '.substr($phone, 7, 2).'';
        }else if(strlen($phone) == 10) {
            return substr($phone, 0, 2).' '.substr($phone, 2, 3).' '.substr($phone, 5, 2).' '.substr($phone, 7, 2).'';
        }
        return $phone;
    }

    /**
     * RETOURNE LA DATE AU FORMAT:'jour mois annee'
     * ex: lundi 31 octobre 2010
     *
     * @param [type] $date
     * @return void
     */
    public function doSomething($date)
    {
        //   $date = new \DateTime($date);
        switch ($date->format('N')) {
            case '01':
                // code...
                $jour = 'lundi';
                break;
            case '02':
                // code...
                $jour = 'mardi';
                break;
            case '03':
                // code...
                $jour = 'mercredi';
                break;
            case '04':
                // code...
                $jour = 'jeurdi';
                break;
            case '05':
                // code...
                $jour = 'vendredi';
                break;
            case '06':
                // code...
                $jour = 'samedi';
                break;
            case '07':
                // code...
                $jour = 'dimanche';
                break;

            default:
                // code...
                return 'Entrer le jour';
                break;
        }
        switch ($date->format('n')) {
            case '1':
                // code...
                $mois = 'Janvier';
                break;
            case '2':
                // code...
                $mois = 'février';
                break;
            case '3':
                // code...
                $mois = 'mars';
                break;
            case '4':
                // code...
                $mois = 'avril';
                break;
            case '5':
                // code...
                $mois = 'mai';
                break;
            case '6':
                // code...
                $mois = 'juin';
                break;
            case '7':
                // code...
                $mois = 'juillet';
                break;
            case '8':
                // code...
                $mois = 'août';
                break;
            case '9':
                // code...
                $mois = 'septembre';
                break;
            case '10':
                // code...
                $mois = 'octobre';
                break;
            case '11':
                // code...
                $mois = 'novembre';
                break;
            case '12':
                // code...
                $mois = 'décembre';
                break;

            default:
                // code...
                break;
        }

        return $jour.' '.$date->format('d').' '.$mois.' '.$date->format('Y');
    }

    /**
     * RETOURNE LA DATE AU FORMAT:'jour mois annee heure et minute'
     * ex: lundi 31 octobre 2010 à 11h30
     *
     * @param [type] $date
     * @return void
     */
    public function dateFormatMinFr($date)
    {
        //   $date = new \DateTime($date);
        switch ($date->format('N')) {
            case '01':
                // code...
                $jour = 'lundi';
                break;
            case '02':
                // code...
                $jour = 'mardi';
                break;
            case '03':
                // code...
                $jour = 'mercredi';
                break;
            case '04':
                // code...
                $jour = 'jeurdi';
                break;
            case '05':
                // code...
                $jour = 'vendredi';
                break;
            case '06':
                // code...
                $jour = 'samedi';
                break;
            case '07':
                // code...
                $jour = 'dimanche';
                break;

            default:
                // code...
                return 'Entrer le jour';
                break;
        }
        switch ($date->format('n')) {
            case '1':
                // code...
                $mois = 'Janvier';
                break;
            case '2':
                // code...
                $mois = 'février';
                break;
            case '3':
                // code...
                $mois = 'mars';
                break;
            case '4':
                // code...
                $mois = 'avril';
                break;
            case '5':
                // code...
                $mois = 'mai';
                break;
            case '6':
                // code...
                $mois = 'juin';
                break;
            case '7':
                // code...
                $mois = 'juillet';
                break;
            case '8':
                // code...
                $mois = 'août';
                break;
            case '9':
                // code...
                $mois = 'septembre';
                break;
            case '10':
                $mois = 'octobre';
                break;
            case '11':
                $mois = 'novembre';
                break;
            case '12':
                $mois = 'décembre';
                break;
            default:
                break;
        }

        return $jour.' '.$date->format('d').' '.$mois.' '.$date->format('Y').' à '.$date->format('h:i');
    }
}
