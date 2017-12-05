<?php

namespace AppBundle\Controller\CMS;

use AppBundle\Domain\Handler\AddEventHandler;
use AppBundle\Domain\Model\Event;
use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/")
     */
    public function renderListAction($max = 10)
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType('event')
            ->orderBy('fields.date', true)
            ->setLimit($max);

        $response = '';
        foreach ($client->getEntries($query) as $event) {
            $response.= $this->renderView('cms/event/_item.html.twig', [
                'event' => $event
            ]);
        }

        return new Response($response);
    }

    /**
     * @Route("/new")
     * @Method(methods={"GET", "POST"})
     * @param Request $request
     * @param AddEventHandler $addEventHandler
     * @return Response
     */
    public function newAction(Request $request, AddEventHandler $addEventHandler)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $addEventHandler->handle($event);
                $this->addFlash('success', 'Votre événement a bien été enregistré, il sera visible après validation !');

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('cms/event/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
