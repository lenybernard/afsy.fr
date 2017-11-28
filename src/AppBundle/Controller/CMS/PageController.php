<?php

namespace AppBundle\Controller\CMS;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/{slug}", name="page_show")
     */
    public function showAction(EngineInterface $twigEngine, $slug = 'homepage')
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType('page')
            ->where('fields.slug', $slug)
            ->setLimit(1);
        $entry = $client->getEntries($query)[0];

        if (!$entry) {
            throw new NotFoundHttpException;
        }

        //seek for category custom template
        $template = sprintf('cms/page/custom/%s.html.twig', $slug);
        if (!$twigEngine->exists($template) ) {
            $template = 'cms/page/show.html.twig';
        }
        // replace this example code with whatever you need
        return $this->render($template, [
            'page' => $entry,
        ]);
    }
}
