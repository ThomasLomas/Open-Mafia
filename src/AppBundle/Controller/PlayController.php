<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlayController extends Controller
{
    /**
     * @Route("/play", name="play_home")
     */
    public function homeAction(Request $request)
    {
        return $this->render('play/home.html.twig');
    }
}
