<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class JsonApiRequestCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var GuzzleClient
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

    /**
     * @var bool
     */
    protected $decodeJsonAsAssoc = true;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;

        // Set for scrapper custom configured Guzzle
        $this->client = new GuzzleClient([
            'timeout' => 60,
            'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            'verify' => false,
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->work();
    }

    abstract protected function work();

    protected function getUrl(string $url, string $method = 'GET', array $options = []): ResponseInterface
    {
        return $this->client->request($method, $url, $options);
    }

    protected function getJsonFromUrl(string $url, string $method = 'GET', array $options = [])
    {
        $response = $this->getUrl($url, $method, $options);

        if (!$this->validateResponse($response)) {
            return null;
        }

        return $this->processResponse($response);
    }

    protected function processResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents(), $this->decodeJsonAsAssoc);

        if (!$this->validateJson($json)) {
            $this->onResponseProcessingFail($response);
        }

        return $json;
    }

    protected function validateJson($json): bool
    {
        return $json !== null;
    }

    protected function validateResponse(ResponseInterface $response)
    {
        return $response->getStatusCode() == 200;
    }

    protected function onBadResponse(ResponseInterface $response)
    {
        throw new \Exception('Response is bad ' . $response->getReasonPhrase());
    }

    protected function onResponseProcessingFail(ResponseInterface $response)
    {
        throw new \Exception('Response can not be processed: ' . $response->getBody()->getContents());
    }
}
