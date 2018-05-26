<?php

namespace AppBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Crawler as MyCrawler;

class CrawlerService
{
    const URLS_BATCH_SIZE = 5;
    const URL_ACCESS_KEY = 'url';

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function browseUrlsAndProcess($urls, $customOptions = null)
    {
        $urlsProcessed = [];

        $batchSize = (count($urls) < self::URLS_BATCH_SIZE) ? count($urls) : self::URLS_BATCH_SIZE;

        $master = curl_multi_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 2,
        ];

        $options = $customOptions ? array_merge($options, $customOptions) : $options;

        for ($i = 0; $i < $batchSize;) {
            $this->addNewCurl($urls, $i, $options, $master);
        }

        do {
            while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);

            if ($execrun != CURLM_OK) {
                break;
            }

            while ($done = curl_multi_info_read($master)) {
                $info = curl_getinfo($done['handle']);

                $urlsProcessed[] = $this->process($info, $done);

                if (array_key_exists($i, $urls)) {
                    $this->addNewCurl($urls, $i, $options, $master);
                }
                curl_multi_remove_handle($master, $done['handle']);
            }
        } while ($running);

        curl_multi_close($master);

        return $urlsProcessed;
    }

    private function process($info, &$done)
    {
        $crawler = new MyCrawler($info['url'], $info['http_code']);

        if (in_array($info['http_code'], [Response::HTTP_OK, Response::HTTP_MOVED_PERMANENTLY]))  {
            $html = curl_multi_getcontent($done['handle']);

            $crawler->setMarfeelizable(
                $this->isMarfeelizable($html)
            );
        } else {
            $crawler->setMarfeelizable(0);
        }

        $this->entityManager->persist($crawler);

        return [
            'url' => $crawler->getUrl(),
            'marfeelizable' => $crawler->isMarfeelizable(),
        ];
    }

    public function isMarfeelizable($html)
    {
        return $this->hasRelevantTitle($html);
    }

    /**
     * @param $html
     *
     * @return bool|false|int
     */
    private function hasRelevantTitle($html)
    {
        $crawler = new Crawler($html);

        $crawler = $crawler->filter('title');

        foreach ($crawler as $domElement) {
            return preg_match("/news|noticias/", $domElement->nodeValue);
        }

        return false;
    }

    /**
     * @param $urls
     * @param $i
     * @param $options
     * @param $master
     */
    private function addNewCurl(&$urls, &$i, &$options, &$master)
    {
        $ch                   = curl_init();
        $options[CURLOPT_URL] = $urls[$i++][self::URL_ACCESS_KEY];
        curl_setopt_array($ch, $options);
        curl_multi_add_handle($master, $ch);
    }
}