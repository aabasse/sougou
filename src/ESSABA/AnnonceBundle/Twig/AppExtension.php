<?php
namespace ESSABA\AnnonceBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isInstanceof', array($this, 'isInstanceof')),
            new \Twig_SimpleFilter('afficherEmoji', array($this, 'afficherEmoji')),
            new \Twig_SimpleFilter('estEmail', array($this, 'estEmail')),
            new \Twig_SimpleFilter('afficherCommune', array($this, 'afficherCommune')),
            new \Twig_SimpleFilter('formatDate', array($this, 'formatDate')),
            new \Twig_SimpleFilter('getSchemaEvenement', array($this, 'getSchemaEvenement')),
        );
    }

    public function isInstanceof($var, $instance) {
        $instance = "ESSABA\AnnonceBundle\Entity\\".$instance;
        return  $var instanceof $instance;
    }

    public function afficherEmoji($var) {
        $var = strip_tags($var);
        return  preg_replace('/&#(\d+);/i', '<span class="emoji">${0}</span>', $var);
    }

    public function afficherCommune($var) {
        $var = strip_tags($var);
        $textAvant = "région ";
        switch ($var[0]) {
            case $var[0] == 'a' || $var[0] == 'o':
                 $textAvant .='d\'';
                break;
            default:
                $textAvant .='de ';
                break;
        }

        switch ($var[0]) {
            case $var == 'dzaoudzi' || $var == 'pamadzi':
                 $textFin = ' (Petite-Terre)';
                break;
            default:
                $textFin = ' (Grande-Terre)';
                break;
        }
        return $textAvant.$var.$textFin;
    }

    public function formatDate($var)
    {
        $okDate = false;
        $dateDuJour = new \DateTime();
        $date = is_string($var) ? date_create($var) : $var;
        

        if($date->format('m-Y') == $dateDuJour->format('m-Y'))
        {
            $difJour = $dateDuJour->format('j') - $date->format('j');
            switch ($difJour) {
                case 0:
                    $aRetourner = 'Ajourd\'huit &agrave; '. $date->format('H:i');
                    $okDate = true;
                    break;
                case 1:
                    $aRetourner = 'Hier &agrave; '. $date->format('H:i');
                    $okDate = true;
                    break;
            }
        }

        if(!$okDate)
        {
            setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
            //$date = date_create($var);
            $aRetourner = strftime("%A %d %B %Y, %H:%M", $date->getTimestamp());
        }
        return utf8_encode($aRetourner);
    }



    public function estEmail($var) {
        return  preg_match('/@/', $var);
    }

    public function getSchemaEvenement($var)
    {
        switch ($var) {
            case 'danse':
                return 'DanceEvent';
                break;
            case 'musical':
                return 'MusicEvent';
                break;
            case 'social':
                return 'SocialEvent';
                break;
            case 'sportive':
                return 'SportsEvent';
                break;
            case 'education':
                return 'EducationEvent';
                break;
            default:
                return 'Event';
                break;
        }
    }

    public function getName()
    {
        return 'app_extension';
    }
}
?>