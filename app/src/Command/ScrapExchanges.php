<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use DateTime;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class ScrapExchanges extends ScrapCommand
{
    protected const BASE_URL = 'https://cryptocoincharts.info/markets/info';
    protected const FORBIDDEN_RESPONSE_LIMIT = 5;
    const ITEMS_CSS_SELECTOR = '.exch-table tbody > tr';
    const ITEM_SITE_CSS_SELECTOR = 'td.statistics_detail a';
    const ITEM_NAME_CSS_SELECTOR = 'h3.title';
    const ITEM_LOGO_CSS_SELECTOR = 'img.logo-img';
    const PARSE_INTERVAL = 'parse_interval';

    protected function configure(): void
    {
        $this
            ->setName('rigpick:scrap-coins')
            ->setDescription('Scrap exchanges from cryptocoincharts')
            ->setHelp('This command scrap website cryptocoincharts.');
    }

    protected function work()
    {
        $this->scrap();
    }

    private function scrap(): void
    {

        $forbiddenCount = 0;
        while ($forbiddenCount < static::FORBIDDEN_RESPONSE_LIMIT) {
            try {
                if ($this->scrapUrl(static::BASE_URL)) {
                    break;
                }
                sleep(5);
                $this->output->writeln('retry...');
            } catch (RequestException $exception) {
                //
            }
        }
        $this->output->writeln('done');
    }


    /**
     * @param string $url
     *
     * @return bool
     */
    private function scrapUrl(string $url): bool
    {
        $crawler = $this->getUrl($url);

        $filtered = $crawler->filter(static::ITEMS_CSS_SELECTOR);
        if (!$filtered->count()) {
            return false;
        }
        $that = $this;
        $crawler->each(function (Crawler $node) use ($that) {
            $a = $node->filter('a.ico_first_line');
            if ($a->count()) {
                $that->scrapItem($a->attr('href'));
            }
            usleep(static::PARSE_INTERVAL);
        });

        return true;
    }

    /**
     * @param string $url
     * @throws Exception
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function scrapItem(string $url)
    {
        $crawler = $this->getUrl($url);

        $title = $crawler->filter(static::ITEM_NAME_CSS_SELECTOR);
        if (!$title->count()) {
            return;
        }
        $site = $crawler->filter(static::ITEM_SITE_CSS_SELECTOR);
        if (!$site->count()) {
            return;
        }
        $titleString = trim(str_replace('information', '', $title->text()));
        $siteUrl = trim($site->attr('href'));
        $siteTitleString = trim(str_replace('website', '', $site->text()));
        if ($titleString != $siteTitleString) {
            throw new Exception(
                'Site titles not equal, looks like page design changed! '
                . $titleString . '!='
                . $siteTitleString
            );
        }

        $this->createExchange($titleString, $siteUrl);
    }

    /**
     * Find or create algorithm and coin by their tickers
     * @param string $name
     * @param string $website
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createExchange(string $name, string $website): bool
    {
        $em = $this->entityManager;
        /**
         * @var ExchangeRepository $repository
         */
        $repository = $em->getRepository(Exchange::class);
        $exchange = $repository->findByNameOrCreate($name);
        $exchange->setWebsite($website);
        $exchange->setSyncedAt(new DateTime());

        $this->saveExchange($exchange);

        return true;
    }

    /**
     * @param Exchange $exchange
     */
    private function saveExchange(Exchange $exchange): void
    {
        $em = $this->entityManager;
        if ($exchange->getId() < 1) {
            $this->output->writeln('+' . $exchange->getName());
        }
        $em->persist($exchange);
        $em->flush();
    }
}
