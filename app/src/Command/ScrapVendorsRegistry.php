<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Vendor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapVendorsRegistry extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $client;

    const BASE_URL = 'https://pcisig.com/membership/member-companies';
    const ITEMS_CSS_SELECTOR = '#content-wrap > div > table > tbody > tr';

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

    protected function configure()
    {
        $this
            ->setName('rigpick:scrap-vendors')
            ->setDescription('Scrap vendors registry from PCI-SIG to DB')
            ->setHelp('This command scrap website PCI-SIG website (https://pcisig.com/membership/member-companies).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->scrap();
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
        $crawler = $this->client->request('GET', $url);

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
            'id' => $id,
        ]);

        if (!$vendor) {
            $vendor = new Vendor();
            $vendor->setId($id);
        }

        $vendor->setName($name);
        $em->persist($vendor);
        $em->flush();
    }
}
