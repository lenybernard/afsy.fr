<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

        return $this->render('post/index.html.twig', [
            'entries' => $client->getEntries($query)
        ]);
    }

    /**
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

        return $this->render('post/show.html.twig', [
            'post' => $client->getEntries($query)[0]
        ]);
    }

}
