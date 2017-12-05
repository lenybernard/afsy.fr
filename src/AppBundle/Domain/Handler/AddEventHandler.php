<?php
namespace AppBundle\Domain\Handler;

use AppBundle\Domain\Model\Event;
use AppBundle\Domain\Transformer\EventToEntryTransformer;
use Contentful\Management\Client;

class AddEventHandler
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var EventToEntryTransformer
     */
    private $transformer;

    public function __construct(Client $client, EventToEntryTransformer $transformer)
    {
        $this->client = $client;
        $this->transformer = $transformer;
    }

    public function handle(Event $event) {
        $entry = $this->transformer->transform($event);
        $this->client->entry->create($entry);
    }
}