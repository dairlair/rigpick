<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180216085129 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE rig_gpu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rig_gpu (id BIGINT NOT NULL, rig_id BIGINT DEFAULT NULL, vga_bios_id INT DEFAULT NULL, count INT NOT NULL, power_limit_percentage INT DEFAULT NULL, gpu_clock INT DEFAULT NULL, gpu_voltage DOUBLE PRECISION DEFAULT NULL, memory_clock INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_33D4D5A75CFBCBCD ON rig_gpu (rig_id)');
        $this->addSql('CREATE INDEX IDX_33D4D5A7410FB24A ON rig_gpu (vga_bios_id)');
        $this->addSql('ALTER TABLE rig_gpu ADD CONSTRAINT FK_33D4D5A75CFBCBCD FOREIGN KEY (rig_id) REFERENCES rigs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rig_gpu ADD CONSTRAINT FK_33D4D5A7410FB24A FOREIGN KEY (vga_bios_id) REFERENCES vga_bios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE rig_gpu_id_seq CASCADE');
        $this->addSql('DROP TABLE rig_gpu');
    }
}
