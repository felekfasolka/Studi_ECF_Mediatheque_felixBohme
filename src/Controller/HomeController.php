<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HomeController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        if (in_array('ROLE_EDITOR', $this->getUser()->getRoles(), true))  {
            return $this->redirect($this->generateUrl('backend'));
        }
        elseif (in_array('ROLE_USER', $this->getUser()->getRoles(), true)) {
            return $this->redirect($this->generateUrl('media'));
    }
        throw new \Exception(AccessDeniedException::class);
}
}
