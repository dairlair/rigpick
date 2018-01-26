<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\GraphicCardModel;
use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use App\Entity\VgaBios;
use App\Repository\GraphicCardModelRepository;
use App\Repository\GraphicCardSeriesRepository;
use App\Repository\VendorRepository;
use App\Repository\VgaBiosRepository;
use App\Services\UrlMarker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ScrapVgaBiosRegistry extends ScrapCommand
{
    protected const BASE_URL = 'https://www.techpowerup.com';

    /**
     * This selector used to find link to each VGA BIOS from page lise that https://www.techpowerup.com/vgabios/?page=2
     */
    protected const LIST_PAGE_ITEM_SELECTOR = 'td.actions > a:contains("Details")';

    private $urlMarker;

    public function __construct(EntityManagerInterface $entityManager, UrlMarker $urlMarker)
    {
        parent::__construct($entityManager);
        $this->urlMarker = $urlMarker;
    }

    protected function configure(): void
    {
        $this
            ->setName('rigpick:scrap-vga-bios')
            ->setDescription('Scrap VGA BIOS list from TechPowerUp to DB')
            ->setHelp('This command scrap website TechPowerUp website (https://www.techpowerup.com/vgabios/).');
    }

    protected function work()
    {
        // TechPowerUp.com starts pages with #1.
        for ($i = 1;; $i++) {
            $this->output->writeln("<info>Scrap list page #$i...</info>");
            if (!$this->scrapListPage($i)) {
                return;
            }
        }
    }

    /**
     * @param int $number
     *
     * @return bool True when page parsed successfully, false otherwise
     */
    private function scrapListPage(int $number): bool
    {
        $url = $this->createListUrl($number);

        return $this->scrapListUrl($url);
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return bool
     */
    private function scrapListUrl(string $url): bool
    {
        $crawler = $this->getUrl($url);

        if (!$crawler->filter(static::LIST_PAGE_ITEM_SELECTOR)->count()) {
            return false;
        }

        $crawler->filter(static::LIST_PAGE_ITEM_SELECTOR)->each(function (Crawler $node) {
            $this->parseItemPage(static::BASE_URL . $node->attr('href'));
        });

        return true;
    }

    private function createListUrl($page = 0): string
    {
        return static::BASE_URL . '/vgabios/?' . http_build_query(['page' => $page]);
    }

    /**
     * @param array $attributes
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function save(array $attributes): void
    {
        $em = $this->entityManager;
        /** @var VendorRepository $vendorRepository */
        $vendorRepository = $em->getRepository(Vendor::class);
        /** @var GraphicCardSeriesRepository $graphicCardSeriesRepository */
        $graphicCardSeriesRepository = $em->getRepository(GraphicCardSeries::class);
        /** @var GraphicCardModelRepository $graphicCardModelRepository */
        $graphicCardModelRepository = $em->getRepository(GraphicCardModel::class);
        /** @var VgaBiosRepository $vgaBiosRepository */
        $vgaBiosRepository = $em->getRepository(VgaBios::class);

        [$gpuVendorId] = explode(' ', $attributes['Device Id']);
        $gpuVendorId = (int) hexdec($gpuVendorId);
        $gpuVendor = $vendorRepository->findByPciSigIdOrFail($gpuVendorId);

        [$cardVendorId] = explode(' ', $attributes['Subsystem Id']);
        $cardVendorId = (int) hexdec($cardVendorId);

        $cardVendor = $vendorRepository->findByNameOrCreate($attributes['Manufacturer'], $cardVendorId);

        $series = $graphicCardSeriesRepository->findByNameOrCreate($gpuVendor, $attributes['Model']);

        $model = $graphicCardModelRepository->findByNameOrCreate($series, $cardVendor, $attributes['Model name']);

        $vgaBiosRepository->findByAttributesOrCreate($model, $attributes);

        $this->entityManager->clear();
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function parseItemPage(string $url): void
    {
        if ($this->urlMarker->isRecentlyUpdated($url)) {
            $this->output->writeln('URL Recently updated and must be skipped. ' . $url);
            return;
        }
        $crawler = $this->getUrl($url);
        $attributes = [];
        $attributes['Model name'] = '';
        if ($crawler->filter('.vgabios-details h2')->count()) {
            $attributes['Model name'] = trim($crawler->filter('.vgabios-details h2')->text(), '()');
        }
        $crawler->filter('.cardinfo table tr')->each(function (Crawler $node) use (&$attributes) {
            $field = trim($node->filter('th')->text(), ':');
            $value = $node->filter('td')->text();
            $attributes[$field] = $value;
        });
        $this->save($attributes);
        $this->output->writeln('URL parsed successfully. ' . $url);
        $this->output->writeln('Memory usage:' . memory_get_usage(true));
        $this->urlMarker->markAsUpdated($url);
    }
}
