<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CommitCrimeType;
use AppBundle\Event\CrimeCommitEvent;
use AppBundle\Event\JailEvent;
use AppBundle\Model\Crime;

class CrimeController extends Controller
{
    /**
     * @Route("/play/crimes", name="crimes_list")
     */
    public function listAction(Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');
        $crime = new Crime;
        $form = $this->createForm(CommitCrimeType::class, $crime);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chance = isset($this->getUser()->getCrimeChances()[$crime->getCrime()]) ?
                $this->getUser()->getCrimeChances()[$crime->getCrime()] : 0;

            $crime->setChance($chance);
            $crimeEvent = new CrimeCommitEvent($crime);

            $crimeRandomChance = mt_rand(1, 100);
            $jailChance = mt_rand(1, 3);

            if($crime->getChance() >= $crimeRandomChance) {
                $dispatcher->dispatch(CrimeCommitEvent::SUCCESS, $crimeEvent);
                $this->addFlash('success', 'This crime was successful');
            } elseif($crime->getChance() < $crimeRandomChance && $jailChance === 1) {
                $jailEvent = new JailEvent([ 'time' => mt_rand(30,70), 'reason' => 'crime' ]);
                $dispatcher->dispatch(CrimeCommitEvent::FAIL, $crimeEvent);
                $dispatcher->dispatch(JailEvent::BOOK, $jailEvent);
                $this->addFlash('danger', 'This crime was unsuccessful and you have been jailed');
            } else {
                $dispatcher->dispatch(CrimeCommitEvent::FAIL, $crimeEvent);
                $this->addFlash('danger', 'This crime was unsuccessful');
            }

            return $this->redirectToRoute('crimes_list');
        }

        return $this->render('crime/list.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
