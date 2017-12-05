<?php

namespace AppBundle\Controller\CMS;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/goals")
 */
class GoalsController extends Controller
{
    /**
     * @Route("/")
     */
    public function renderListAction()
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType('goal');

        $response = '';
        foreach ($client->getEntries($query) as $goal) {
            $response.= $this->renderView('cms/goals/_item.html.twig', [
                'goal' => $goal
            ]);
        }

        return new Response($response);
    }
}