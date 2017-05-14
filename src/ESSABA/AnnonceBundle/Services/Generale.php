<?php
namespace ESSABA\AnnonceBundle\Services;

/**
* 
*/
class Generale
{
	public function date_fr_to_mysql($dateFr)
    {
    	if(is_string($dateFr))
    	{
			$elementDateHeur = explode(" ", $dateFr );
	        $elementDate = explode("/", $elementDateHeur[0] );
	        $newDatelieu = $elementDate[2].'-'.$elementDate[1].'-'.$elementDate[0].' '.$elementDateHeur[1];
	        return new \DateTime($newDatelieu);
    	}
    	return $dateFr;
    }
}

?>