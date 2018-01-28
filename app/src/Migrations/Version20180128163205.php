<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180128163205 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE coin ADD source TEXT NOT NULL');
        $this->addSql('ALTER TABLE coin ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE coin DROP external_id');
        $this->addSql('ALTER TABLE coin ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_time DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward24 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward3 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward7 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER last_block DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty24 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty3 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty7 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER hashrate DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate24 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate3 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate7 DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr TYPE TEXT');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_vol DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER market_cap_usd DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER status TYPE TEXT');
        $this->addSql('ALTER TABLE coin ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER synced_at DROP NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER website TYPE TEXT');
        $this->addSql('ALTER TABLE coin ALTER website DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER website DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5569975D5E237E06 ON coin (name)');
        $this->addSql('CREATE UNIQUE INDEX coin_unique_source_extid ON coin (source, source_id)');
        $this->addSql('ALTER INDEX coin_unique_ticker RENAME TO UNIQ_5569975D7EC30896');
        $this->addSql('ALTER TABLE algorithms ALTER description DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A926C1EF5E237E06 ON algorithms (name)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_5569975D5E237E06');
        $this->addSql('DROP INDEX coin_unique_source_extid');
        $this->addSql('ALTER TABLE coin ADD external_id INT NOT NULL');
        $this->addSql('ALTER TABLE coin DROP source');
        $this->addSql('ALTER TABLE coin DROP source_id');
        $this->addSql('ALTER TABLE coin ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_time SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward24 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward3 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER block_reward7 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER last_block SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty24 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty3 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER difficulty7 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER hashrate SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate24 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate3 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate7 SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_curr SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER exchange_rate_vol SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER market_cap_usd SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE coin ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER synced_at SET NOT NULL');
        $this->addSql('ALTER TABLE coin ALTER website TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE coin ALTER website DROP DEFAULT');
        $this->addSql('ALTER TABLE coin ALTER website SET NOT NULL');
        $this->addSql('ALTER INDEX uniq_5569975d7ec30896 RENAME TO coin_unique_ticker');
        $this->addSql('DROP INDEX UNIQ_A926C1EF5E237E06');
        $this->addSql('ALTER TABLE algorithms ALTER description SET NOT NULL');
    }
}
