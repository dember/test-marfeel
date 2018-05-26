<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CrawlerControllerTest extends WebTestCase
{
    public function testSpider()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/browse',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            file_get_contents('%kernel.root_dir%/../web/uploads/sites.json')
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}