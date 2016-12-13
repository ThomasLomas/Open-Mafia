<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/play/online", name="profile_online")
     */
    public function onlineAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findOnlineOrderedByUsername();

        return $this->render('profile/online.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/play/profile/{id}", name="profile_view")
     */
    public function viewAction(Request $request)
    {

    }
}
