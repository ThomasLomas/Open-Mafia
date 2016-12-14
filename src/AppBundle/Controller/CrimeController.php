<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CommitCrimeType;
use AppBundle\Event\CrimeCommitEvent;

class CrimeController extends Controller
{
    /**
     * @Route("/play/crimes", name="crimes_list")
     */
    public function listAction(Request $request)
    {
        $form = $this->createForm(CommitCrimeType::class, ['crime' => 0]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher = $this->get('event_dispatcher');
            $event = new CrimeCommitEvent($form->getData());
            $dispatcher->dispatch(CrimeCommitEvent::NAME, $event);
        }

        return $this->render('crime/list.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
