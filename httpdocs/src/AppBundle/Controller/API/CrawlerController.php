<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class CrawlerController extends Controller
{
    /**
     * Browse through given URLS
     *
     * @Route("/v1/browse", name="track_index")
     * @Method("GET")
     */
    public function browseAction(Request $request)
    {
        $json = '[
            {
                "url": "c-and-a.com"
            },
            {
                "url": "toshiba.es"
            }
        ]';

        $urls = json_decode($json, true);
    }
}