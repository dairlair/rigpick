<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class ScrapCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;

        $this->client = new Client();
        // Set for scrapper custom configured Guzzle
        $guzzleClient = new GuzzleClient([
            'timeout' => 60,
            'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            'verify' => false,
        ]);
        $this->client->setClient($guzzleClient);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->work();
    }

    abstract protected function work();

    protected function getUrl(string $url): Crawler
    {
        $this->client->getHistory()->clear();

        return $this->client->request('GET', $url);
    }
}
