<?php

namespace Aiconoa\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/profile/{userId}")
     */
    public function profileAction($userId)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException("You must login to access this page");
        }

        if($this->getUser()->getId() != $userId) {
            throw $this->createAccessDeniedException("You can't access someone else profile");
        }

        return $this->render("AiconoaUserBundle:Default:profile.html.twig");
    }
}
