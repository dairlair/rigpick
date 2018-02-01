<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatView extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    private const MATERIALIZED_VIEWS = [
        'vga_bios_index',
    ];

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('rigpick:mat-view')
            ->setDescription('Refresh all materialized views')
            ->setHelp('This command allows you to refresh all materialized views');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (self::MATERIALIZED_VIEWS as $matView) {
            $this->refreshMaterializedView($matView);
            $output->writeln('Materialized view "' . $matView . '" refreshed successfully');
        }
    }

    /**
     * @param string $matView
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function refreshMaterializedView(string $matView): void
    {
        $sql = 'REFRESH MATERIALIZED VIEW CONCURRENTLY "' . $matView . '" WITH DATA';
        $statement = $this->em->getConnection()->prepare($sql);
        $statement->execute();
    }
}
