<?php

namespace AppBundle\Controller\CMS;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Templating\EngineInterface;

class PostController extends Controller
{
    const CONTENT_TYPE_POST = '2wKn6yEnZewu2SCCkus4as';
    const CONTENT_TYPE_CATEGORY = '5KMiN6YPvi42icqAUQMCQe';
    const CONTENT_TYPE_AUTHOR = '1kUEViTN4EmGiEaaeC6ouY';

    /**
     * @Route("/blog")
     */
    public function indexAction()
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType(self::CONTENT_TYPE_POST)->orderBy('fields.date', true);

        return $this->render('cms/post/index.html.twig', [
            'entries' => $client->getEntries($query)
        ]);
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
