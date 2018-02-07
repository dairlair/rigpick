<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Algorithm;
use App\Entity\Coin;
use DateTime;
use Exception;
use GuzzleHttp\Exception\RequestException;

class ScrapCoins extends JsonApiRequestCommand
{
    protected const BASE_URL = 'https://whattomine.com/coins/{id}.json';
    /**
     *  Parse insterval in microseconds
     */
    protected const PARSE_INTERVAL = 1000000;
    protected const FORBIDDEN_RESPONSE_LIMIT = 50;
    protected const MAX_ID = 250;

    protected function configure(): void
    {
        $this
            ->setName('rigpick:scrap-coins')
            ->setDescription('Scrap coins registry from Whattomine')
            ->setHelp('This command scrap website Whattomine (' . static::BASE_URL . ').');
    }

    protected function work()
    {
        $this->scrap();
    }

    private function scrap(): void
    {
        $i = 1;
        $forbiddenCount = 0;
        while (true) {
            try {
                if ($this->scrapPage($i)) {
                    $forbiddenCount = 0;
                }
            } catch (RequestException $exception) {
                if ($exception->getCode() == 403) {
                    $forbiddenCount++;
                    $this->output->writeln('forbidden count: ' . $forbiddenCount);
                    if ($forbiddenCount > static::FORBIDDEN_RESPONSE_LIMIT) {
                        echo $exception->getMessage();
                        return;
                    }
                }
            }
            $i++;
            if ($i > static::MAX_ID) {
                $this->output->writeln('done');
                return;
            }
            usleep(static::PARSE_INTERVAL);
        }
    }

    /**
     * @param int $number
     *
     * @return bool True when page parsed successfully, false otherwise
     * @throws Exception
     */
    private function scrapPage(int $number): bool
    {
        $url = $this->createUrl($number);

        return $this->scrapUrl($url);
    }

    private function createUrl($page = 0): string
    {
        return str_replace('{id}', $page, static::BASE_URL);
    }

    /**
     * @param string $url
     *
     * @return bool
     * @throws \Exception
     */
    private function scrapUrl(string $url): bool
    {
        $json = $this->getJsonFromUrl($url);

        if (isset($json['error']) && strpos($json['error'], 'Could not find active coin with id') !== false) {
            return false;
        }

        if (!isset($json['id'])) {
            throw new Exception('Id not found in json ' . print_r($json, true));
        }


        return $this->processData($json);
    }

    /**
     * Find or create algorithm and coin by their tickers
     * @param array $json
     * @return bool
     */
    public function processData(array $json): bool
    {
        $em = $this->entityManager;

        $coin = $em->getRepository(Coin::class)->findOneBy([
            'ticker' => $json['tag'],
        ]);

        if (!$coin) {
            $algorithm = $this->findOrCreateAlgorithm($json['algorithm']);
            $coin = $this->createCoinFromJson($algorithm, $json);
        }
        $this->updateCoinDataFromJson($coin, $json);
        $this->saveCoin($coin);

        return true;
    }

    private function findOrCreateAlgorithm(string $name): Algorithm
    {
        $em = $this->entityManager;
        $ticker = trim(strtolower($name));
        $algorithm = $em->getRepository(Algorithm::class)->findOneBy([
            'ticker' => $ticker,
        ]);

        if (!$algorithm) {
            $algorithm = new Algorithm();
            $algorithm->setTicker($ticker);
            $algorithm->setName($name);
            $this->saveAlgorithm($algorithm);
        }

        return $algorithm;
    }

    private function createCoinFromJson(Algorithm $algorithm, array $json)
    {
        $coin = new Coin();
        $coin->setSourceId((int)$json['id']);
        $coin->setName((string)$json['name']);
        $coin->setTicker((string)$json['tag']);
        $coin->setAlgorithm($algorithm);
        $coin->setSource(Coin::WHAT_TO_MINE);

        return $coin;
    }

    public function updateCoinDataFromJson(Coin $coin, array $json): void
    {
        $coin->setBlockTime((int)$json['block_time']);
        $coin->setBlockReward((float)$json['block_reward']);
        $coin->setBlockReward24((float)$json['block_reward24']);
        $coin->setBlockReward3((float)$json['block_reward3']);
        $coin->setBlockReward7((float)$json['block_reward7']);
        $coin->setLastBlock((int)$json['last_block']);
        $coin->setDifficulty((float)$json['difficulty']);
        $coin->setDifficulty24((float)$json['difficulty24']);
        $coin->setDifficulty3((float)$json['difficulty3']);
        $coin->setDifficulty7((float)$json['difficulty7']);
        $coin->setHashrate((float)$json['nethash']);
        $coin->setExchangeRate((float)$json['exchange_rate']);
        $coin->setExchangeRate24((float)$json['exchange_rate24']);
        $coin->setExchangeRate3((float)$json['exchange_rate3']);
        $coin->setExchangeRate7((float)$json['exchange_rate7']);
        $coin->setExchangeRateVol((float)$json['exchange_rate_vol']);
        $coin->setExchangeRateCurr((string)$json['exchange_rate_curr']);
        $coin->setMarketCapUsd((float)str_replace('$', '', $json['market_cap']));
        $coin->setStatus((string)$json['status']);
        $coin->setLagging((bool)$json['lagging']);
        $coin->setSyncedAt((new DateTime())->setTimestamp((int)$json['timestamp']));
    }

    private function saveCoin(Coin $coin): void
    {
        $em = $this->entityManager;
        if ($coin->getId() < 1) {
            $this->output->writeln('+' . $coin->getTicker());
        }
        $em->persist($coin);
        $em->flush();
    }

    private function saveAlgorithm(Algorithm $algorithm): void
    {
        $em = $this->entityManager;
        if ($algorithm->getId() < 1) {
            $this->output->writeln('+algo ' . $algorithm->getTicker());
        }
        $em->persist($algorithm);
        $em->flush();
    }


}
