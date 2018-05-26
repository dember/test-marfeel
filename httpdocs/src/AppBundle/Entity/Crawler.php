<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Crawler
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CrawlerRepository")
 */
class Crawler
{
    use TimestampableEntity;

    public function __construct($url, $statusCode)
    {
        $this->url        = $url;
        $this->statusCode = $statusCode;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $statusCode;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $marfeelizable;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Crawler
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Crawler
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     *
     * @return Crawler
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMarfeelizable()
    {
        return $this->marfeelizable;
    }

    /**
     * @param bool $marfeelizable
     *
     * @return Crawler
     */
    public function setMarfeelizable($marfeelizable)
    {
        $this->marfeelizable = $marfeelizable;

        return $this;
    }
}