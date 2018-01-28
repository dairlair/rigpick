<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180128160728 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE coin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE algorithms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coin (id INT NOT NULL, algorithm_id INT DEFAULT NULL, external_id INT NOT NULL, ticker TEXT NOT NULL, name TEXT NOT NULL, description TEXT NOT NULL, block_time DOUBLE PRECISION NOT NULL, block_reward DOUBLE PRECISION NOT NULL, block_reward24 DOUBLE PRECISION NOT NULL, block_reward3 DOUBLE PRECISION NOT NULL, block_reward7 DOUBLE PRECISION NOT NULL, last_block INT NOT NULL, difficulty DOUBLE PRECISION NOT NULL, difficulty24 DOUBLE PRECISION NOT NULL, difficulty3 DOUBLE PRECISION NOT NULL, difficulty7 DOUBLE PRECISION NOT NULL, hashrate INT NOT NULL, exchange_rate DOUBLE PRECISION NOT NULL, exchange_rate24 DOUBLE PRECISION NOT NULL, exchange_rate3 DOUBLE PRECISION NOT NULL, exchange_rate7 DOUBLE PRECISION NOT NULL, exchange_rate_curr VARCHAR(255) NOT NULL, exchange_rate_vol DOUBLE PRECISION NOT NULL, market_cap_usd DOUBLE PRECISION NOT NULL, lagging BOOLEAN NOT NULL, status VARCHAR(255) NOT NULL, synced_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reviewed BOOLEAN NOT NULL, website VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5569975DBBEB6CF7 ON coin (algorithm_id)');
        $this->addSql('CREATE UNIQUE INDEX coin_unique_ticker ON coin (ticker)');
        $this->addSql('CREATE TABLE algorithms (id INT NOT NULL, coins_id INT DEFAULT NULL, name TEXT NOT NULL, ticker TEXT NOT NULL, description TEXT NOT NULL, reviewed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A926C1EFAFCBC0D3 ON algorithms (coins_id)');
        $this->addSql('CREATE UNIQUE INDEX algorithm_unique_ticker ON algorithms (ticker)');
        $this->addSql('ALTER TABLE coin ADD CONSTRAINT FK_5569975DBBEB6CF7 FOREIGN KEY (algorithm_id) REFERENCES algorithms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE algorithms ADD CONSTRAINT FK_A926C1EFAFCBC0D3 FOREIGN KEY (coins_id) REFERENCES coin (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE algorithms DROP CONSTRAINT FK_A926C1EFAFCBC0D3');
        $this->addSql('ALTER TABLE coin DROP CONSTRAINT FK_5569975DBBEB6CF7');
        $this->addSql('DROP SEQUENCE coin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE algorithms_id_seq CASCADE');
        $this->addSql('DROP TABLE coin');
        $this->addSql('DROP TABLE algorithms');
    }
}
