<?php
namespace UserBundle\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\Utilisateur;

/**
* 
*/
class GestionLogout implements LogoutSuccessHandlerInterface
{
    private $router;
    private $security;
    private $em;

    public function __construct(RouterInterface $router, TokenStorage $security, EntityManager $em)
    {
        $this->router = $router;
        $this->security = $security;
        $this->em = $em;
    }

	public function onLogoutSuccess(Request $request)
    {
        if($this->security->getToken() != null)
        {
            $user = $this->security->getToken()->getUser();
            $user->setDateDeco(new \DateTime('now'));
            $this->em->flush();
        }
        
        return new RedirectResponse($this->router->generate('essaba_annonce_homepage'));
    }
}

?>