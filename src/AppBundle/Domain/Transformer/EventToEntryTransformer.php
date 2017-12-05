<?php
namespace AppBundle\Domain\Transformer;

use AppBundle\Domain\Model\Event;
use Contentful\Management\Resource\Entry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EventToEntryTransformer implements DataTransformerInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * EventToEntryTransformer constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param Event $event
     * @return Entry
     */
    public function transform($event)
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        $entry = new Entry('event');
        $entry->setField('title', $locale, $event->getTitle());
        $entry->setField('date', $locale, $event->getDate()->format('c'));
        $entry->setField('location', $locale, [
            "lat" => $event->getLatitude(),
            "lon" => $event->getLongitude()
        ]);
        $entry->setField('description', $locale, $event->getDescription());
        $entry->setField('tags', $locale, $event->getTags());
        $entry->setField('link', $locale, $event->getLink());
        $entry->setField('slug', $locale, uniqid('event_', true));

        return $entry;
    }

    /**
     * @param Entry $entry
     * @return Event
     */
    public function reverseTransform($entry)
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        $event = new Event();
        $event->setTitle($entry->getField('title'), $locale);
        $event->setDate($entry->getField('date'), $locale);
        $event->setLatitude($entry->getField('location')->getField('latitude'), $locale);
        $event->setLongitude($entry->getField('location')->getField('longitude'), $locale);
        $event->setDescription($entry->getField('description'), $locale);
        $event->setTags($entry->getField('tags'), $locale);
        $event->setLink($entry->getField('link'), $locale);

        return $event;
    }
}