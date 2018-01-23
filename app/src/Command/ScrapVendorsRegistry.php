<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Vendor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ScrapVendorsRegistry extends ScrapCommand
{
    protected const BASE_URL = 'https://pcisig.com/membership/member-companies';
    protected const ITEMS_CSS_SELECTOR = '#content-wrap > div > table > tbody > tr';

    protected function configure(): void
    {
        $this
            ->setName('rigpick:scrap-vendors')
            ->setDescription('Scrap vendors registry from PCI-SIG to DB')
            ->setHelp('This command scrap website PCI-SIG website (https://pcisig.com/membership/member-companies).');
    }

    protected function work()
    {
        $this->scrap();
        // Fix some missing values.
        $this->save(4098, 'AMD'); // 0x1002, ATI Brand, purchased by AMD
    }

    private function scrap(): void
    {
        for ($i = 0;; $i++) {
            if (!$this->scrapPage($i)) {
                return;
            }
        }
    }

    /**
     * @param int $number
     *
     * @return bool True when page parsed successfully, false otherwise
     */
    private function scrapPage(int $number): bool
    {
        $url = $this->createUrl($number);

        return $this->scrapUrl($url);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    private function scrapUrl(string $url): bool
    {
        $crawler = $this->getUrl($url);

        $that = $this;

        if (!$crawler->filter(static::ITEMS_CSS_SELECTOR)->count()) {
            return false;
        }

        $crawler->filter(static::ITEMS_CSS_SELECTOR)->each(function (Crawler $node) use ($that) {
            [$vendorId] = explode(' ', trim($node->filter('td.views-field-field-vendor-id')->text()));
            $name = trim($node->filter('td.views-field-field-name')->text());
            $that->save((int)$vendorId, $name);
        });

        return true;
    }

    private function createUrl($page = 0): string
    {
        return static::BASE_URL . '?' . http_build_query(['page' => $page]);
    }

    private function save(int $id, string $name): void
    {
        $em = $this->entityManager;

        $vendor = $em->getRepository(Vendor::class)->findOneBy([
            'pciSigId' => $id,
        ]);

        if (!$vendor) {
            $vendor = new Vendor();
            $vendor->setPciSigId($id);
        }

        $vendor->setName($name);
        $em->persist($vendor);
        $em->flush();
    }
}
