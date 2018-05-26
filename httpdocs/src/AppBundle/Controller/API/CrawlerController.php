<?php

namespace AppBundle\Controller\API;

use AppBundle\Service\CrawlerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrawlerController extends Controller
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Browse through given URLS
     *
     * @Route("/v1/browse", name="crawler_browse")
     * @Method("POST")
     */
    public function browseAction(Request $request, CrawlerService $crawlerService)
    {
        $urls = [];

        if ($content = $request->getContent()) {
            $urls = json_decode($content, true);
        }

        $urlsProcessed = $crawlerService->browseUrlsAndProcess($urls);

        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $exception) {
            return new JsonResponse(
                ['exception' => $exception],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($urlsProcessed, Response::HTTP_CREATED);
    }
}