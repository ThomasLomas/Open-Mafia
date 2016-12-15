<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JailController extends Controller
{
    /**
     * @Route("/play/jail", name="jail_list")
     */
    public function listAction(Request $request)
    {
        return $this->render('jail/list.html.twig');
    }
}
