<?php
namespace AppBundle\Domain\Model;

use Contentful\Location;
use Symfony\Component\Validator\Constraints as Assert;

class Event
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var \DateTime
     */
    private $date;
    /**
     * @var float
     */
    private $latitude;
    /**
     * @var float
     */
    private $longitude;
    /**
     * @var string
     */
    private $description;
    /**
     * @var array
     */
    private $tags = [];
    /**
     * @var string
     * @Assert\Url()
     */
    private $link;

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Event
     */
    public function setTitle(string $title): Event
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Event
     */
    public function setDate(\DateTime $date): Event
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Event
     */
    public function setDescription(string $description): Event
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return Event
     */
    public function setTags(array $tags): Event
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Event
     */
    public function setLink(string $link): Event
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param string $latitude
     * @return Event
     */
    public function setLatitude(string $latitude): Event
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     * @return Event
     */
    public function setLongitude(float $longitude): Event
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}