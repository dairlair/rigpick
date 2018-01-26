<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180128211739 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE rigs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rigs (id BIGINT NOT NULL, user_id INT DEFAULT NULL, hash TEXT NOT NULL, name TEXT NOT NULL, description TEXT DEFAULT NULL, power INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_44C49A42A76ED395 ON rigs (user_id)');
        $this->addSql('CREATE UNIQUE INDEX rigs_unique_hash ON rigs (hash)');
        $this->addSql('CREATE UNIQUE INDEX rigs_unique_name ON rigs (name)');
        $this->addSql('ALTER TABLE rigs ADD CONSTRAINT FK_44C49A42A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE rigs_id_seq CASCADE');
        $this->addSql('DROP TABLE rigs');
    }
}
