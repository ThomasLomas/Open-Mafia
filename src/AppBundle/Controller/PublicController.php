<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends Controller
{
    /**
     * @Route("/", name="public_home")
     */
    public function homeAction(Request $request)
    {
        return $this->render('public/home.html.twig');
    }

    /**
     * @Route("/register", name="public_register")
     */
    public function registerAction(Request $request)
    {
        return $this->render('public/register.html.twig');
    }
}
