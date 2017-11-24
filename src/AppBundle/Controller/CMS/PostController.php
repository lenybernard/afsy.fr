<?php

namespace AppBundle\Controller\CMS;

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

        return $this->render('cms/post/index.html.twig', [
            'entries' => $client->getEntries($query)
        ]);
    }

    /**
     * @Route("/calendrier-avent-2017")
     */
    public function advent2017Action()
    {
        $client = $this->get('contentful.delivery');
        $query = new \Contentful\Delivery\Query;
        $query->setContentType(self::CONTENT_TYPE_POST)
            ->where('fields.category.sys.id', '2xh0nTld5O8kIsu0Qqs0IQ')
            ->orderBy('fields.date');

        return $this->render('cms/post/advent.html.twig', [
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

}
