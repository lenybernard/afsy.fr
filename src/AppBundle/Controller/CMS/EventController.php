<?php

namespace AppBundle\Controller\CMS;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

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
            $response.= $this->renderView(':cms/event:_item.html.twig', [
                'event' => $event
            ]);
        }

        return new Response($response);
    }

    /**
     * @Route("/blog/{slug}")
     */
    public function showAction($slug)
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType(self::CONTENT_TYPE_POST)
            ->where('fields.slug', $slug, 'match')
            ->setLimit(1);

        return $this->render('cms/post/show.html.twig', [
            'post' => $client->getEntries($query)[0]
        ]);
    }

    /**
     * @Route("/blog/category/{slug}")
     */
    public function listByCategoryAction($slug, EngineInterface $twigEngine)
    {
        $client = $this->get('contentful.delivery');
        //find first the category
        $query = new \Contentful\Delivery\Query;
        $query->setContentType(self::CONTENT_TYPE_CATEGORY)
            ->where('fields.slug', $slug)
            ->setLimit(1);
        $category = $client->getEntries($query)[0];

        //find posts by category
        $query = new \Contentful\Delivery\Query;
        $query->setContentType(self::CONTENT_TYPE_POST)
            ->where('fields.category.sys.id', $category->getId())
            ->orderBy('fields.date');

        //seek for category custom template
        $template = sprintf('cms/category/custom/%s.html.twig', $slug);
        if (!$twigEngine->exists($template) ) {
            $template = 'cms/post/index.html.twig';
        }

        return $this->render($template, [
            'title' => $category->getTitle(),
            'entries' => $client->getEntries($query)
        ]);
    }

}
