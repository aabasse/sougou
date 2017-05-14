<?php

namespace ESSABA\AnnonceBundle\Repository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends \Doctrine\ORM\EntityRepository
{
	function getMessage($idMessage, $utilisateur){
		//IDENTITY(m.conversation) as idConversation
		return $this->getEntityManager()->createQuery(
		     "SELECT m, c
		    FROM ESSABAAnnonceBundle:Message m LEFT join m.conversation c
		    WHERE m.id = :idmessage
		    and (c.utilisateur1 = :utilisateur or c.utilisateur2 = :utilisateur) "
		)
		->setParameter('idmessage', $idMessage)
		->setParameter('utilisateur', $utilisateur)
		->getOneOrNullResult();
	}

	function getNvoMessages($idConversation, $utilisateur)
	{
		$conversations = null;
		$conversations =  $this->getEntityManager()->createQuery(
	    "SELECT m.id, m.contenu, m.vu, m.created, IDENTITY(m.expediteur) as idExpediteur
	    FROM ESSABAAnnonceBundle:Message m join m.conversation c
	    WHERE m.conversation = :conversation
	    and (c.utilisateur1 = :utilisateur or c.utilisateur2 = :utilisateur)
	    and m.vu = 0
	    and m.expediteur <> :utilisateur
		order by m.created")
		->setParameter('conversation', $idConversation)
		->setParameter('utilisateur', $utilisateur)
		->getArrayResult();
		return $conversations;
	}
}
