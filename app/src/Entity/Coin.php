<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoinRepository")
 * @ORM\Table(name="coin", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="coin_unique_source_extid", columns={"source","source_id"})
 * })
 */
class Coin
{
    const INTERNAL = 'internal';
    const WHAT_TO_MINE = 'what_to_mine';

    const SOURCES = [
        self::INTERNAL,
        self::WHAT_TO_MINE
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Algorithm")
     */
    private $algorithm;

    /**
     * @ORM\Column(type="text")
     */
    private $source = self::INTERNAL;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sourceId;

    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $ticker;

    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $block_time;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $block_reward;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $block_reward24;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $block_reward3;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $block_reward7;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    private $last_block;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $difficulty;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $difficulty24;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $difficulty3;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $difficulty7;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $hashrate;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $exchange_rate;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $exchange_rate24;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $exchange_rate3;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $exchange_rate7;
    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $exchange_rate_curr;
    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $exchange_rate_vol;

    /**
     * Market cap in usd
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $market_cap_usd;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $lagging = false;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $status = 'Active';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $syncedAt;
    /**
     * @ORM\Column(type="boolean")
     */
    private $reviewed = false;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $website;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Algorithm
     */
    public function getAlgorithm(): Algorithm
    {
        return $this->algorithm;
    }

    /**
     * @param Algorithm $algorithm
     */
    public function setAlgorithm(Algorithm $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return mixed
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticker
     */
    public function setTicker(string $ticker): void
    {
        $this->ticker = $ticker;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    /**
     * @param float $difficulty
     */
    public function setDifficulty(float $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return int
     */
    public function getBlockTime(): int
    {
        return $this->block_time;
    }

    /**
     * @param int $time
     */
    public function setBlockTime(int $time): void
    {
        $this->block_time = $time;
    }

    /**
     * @return float
     */
    public function getBlockReward(): float
    {
        return $this->block_reward;
    }

    /**
     * @param float $reward
     */
    public function setBlockReward(float $reward): void
    {
        $this->block_reward = $reward;
    }

    /**
     * @return float
     */
    public function getBlockReward24(): float
    {
        return $this->block_reward24;
    }

    /**
     * @param float $reward
     */
    public function setBlockReward24(float $reward): void
    {
        $this->block_reward24 = $reward;
    }

    /**
     * @return mixed
     */
    public function getBlockReward3(): float
    {
        return $this->block_reward3;
    }

    /**
     * @param float $reward
     */
    public function setBlockReward3(float $reward): void
    {
        $this->block_reward3 = $reward;
    }

    /**
     * @return float
     */
    public function getBlockReward7(): float
    {
        return $this->block_reward7;
    }

    /**
     * @param float $reward
     */
    public function setBlockReward7(float $reward): void
    {
        $this->block_reward7 = $reward;
    }

    /**
     * @return int
     */
    public function getLastBlock(): int
    {
        return $this->last_block;
    }

    /**
     * @param int $lastBlock
     */
    public function setLastBlock(int $lastBlock): void
    {
        $this->last_block = $lastBlock;
    }

    /**
     * @return float
     */
    public function getDifficulty24(): float
    {
        return $this->difficulty24;
    }

    /**
     * @param float $difficulty
     */
    public function setDifficulty24(float $difficulty): void
    {
        $this->difficulty24 = $difficulty;
    }

    /**
     * @return float
     */
    public function getDifficulty3(): float
    {
        return $this->difficulty3;
    }

    /**
     * @param float $diff
     */
    public function setDifficulty3(float $diff): void
    {
        $this->difficulty3 = $diff;
    }

    /**
     * @return mixed
     */
    public function getDifficulty7(): float
    {
        return $this->difficulty7;
    }

    /**
     * @param mixed $diff
     */
    public function setDifficulty7(float $diff): void
    {
        $this->difficulty7 = $diff;
    }

    /**
     * @return float
     */
    public function getHashrate(): float
    {
        return $this->hashrate;
    }

    /**
     * @param float $hashRate
     */
    public function setHashrate(float $hashRate): void
    {
        $this->hashrate = $hashRate;
    }

    /**
     * @return float
     */
    public function getExchangeRate(): float
    {
        return $this->exchange_rate;
    }

    /**
     * @param float $rate
     */
    public function setExchangeRate(float $rate): void
    {
        $this->exchange_rate = $rate;
    }

    /**
     * @return float
     */
    public function getExchangeRate24(): float
    {
        return $this->exchange_rate24;
    }

    /**
     * @param float $rate
     */
    public function setExchangeRate24(float $rate): void
    {
        $this->exchange_rate24 = $rate;
    }

    /**
     * @return mixed
     */
    public function getExchangeRate3(): float
    {
        return $this->exchange_rate3;
    }

    /**
     * @param float $rate
     */
    public function setExchangeRate3(float $rate): void
    {
        $this->exchange_rate3 = $rate;
    }

    /**
     * @return float
     */
    public function getExchangeRate7(): float
    {
        return $this->exchange_rate7;
    }

    /**
     * @param float $rate
     */
    public function setExchangeRate7(float $rate): void
    {
        $this->exchange_rate7 = $rate;
    }

    /**
     * @return string
     */
    public function getExchangeRateCurr(): string
    {
        return $this->exchange_rate_curr;
    }

    /**
     * @param string $currency
     */
    public function setExchangeRateCurr(string $currency): void
    {
        $this->exchange_rate_curr = $currency;
    }

    /**
     * @return float
     */
    public function getExchangeRateVol(): float
    {
        return $this->exchange_rate_vol;
    }

    /**
     * @param mixed $volume
     */
    public function setExchangeRateVol(float $volume): void
    {
        $this->exchange_rate_vol = $volume;
    }

    /**
     * @return float
     */
    public function getMarketCapUsd(): float
    {
        return $this->market_cap_usd;
    }

    /**
     * @param float $amount
     */
    public function setMarketCapUsd(float $amount): void
    {
        $this->market_cap_usd = $amount;
    }

    /**
     * @return bool
     */
    public function getLagging(): bool
    {
        return $this->lagging;
    }

    /**
     * @param bool $lagging
     */
    public function setLagging(bool $lagging): void
    {
        $this->lagging = $lagging;
    }

    /**
     * @return DateTime
     */
    public function getSyncedAt(): DateTime
    {
        return $this->syncedAt;
    }

    /**
     * @param DateTime $syncedAt
     */
    public function setSyncedAt(DateTime $syncedAt): void
    {
        $this->syncedAt = $syncedAt;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     */
    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function getReviewed(): bool
    {
        return $this->reviewed;
    }

    /**
     * @param bool $reviewed
     */
    public function setReviewed(bool $reviewed): void
    {
        $this->reviewed = $reviewed;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @throws \Exception
     */
    public function setSource(string $source): void
    {
        if (!in_array($source, static::SOURCES)) {
            throw new \Exception('Unexpected source ' . $source);
        }
        $this->source = $source;
    }
}
