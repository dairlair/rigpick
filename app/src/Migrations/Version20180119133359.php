<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180119133359 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE graphic_card_models_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE graphic_card_series_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE urls_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vendor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vga_bios_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE graphic_card_models (id INT NOT NULL, series_id INT DEFAULT NULL, vendor_id INT DEFAULT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3A750F65278319C ON graphic_card_models (series_id)');
        $this->addSql('CREATE INDEX IDX_B3A750F6F603EE73 ON graphic_card_models (vendor_id)');
        $this->addSql('CREATE UNIQUE INDEX graphic_card_models_unique_vendor_name ON graphic_card_models (vendor_id, series_id, name)');
        $this->addSql('CREATE TABLE graphic_card_series (id INT NOT NULL, vendor_id INT DEFAULT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D6161D2F603EE73 ON graphic_card_series (vendor_id)');
        $this->addSql('CREATE UNIQUE INDEX graphic_card_series_unique_vendor_name ON graphic_card_series (vendor_id, name)');
        $this->addSql('CREATE TABLE urls (id BIGINT NOT NULL, url TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX urls_unique_url ON urls (url)');
        $this->addSql('CREATE TABLE vga_bios (id INT NOT NULL, model_id INT DEFAULT NULL, device_id BIGINT NOT NULL, subsystem_id BIGINT NOT NULL, interface VARCHAR(255) NOT NULL, memory_size BIGINT NOT NULL, gpu_clock BIGINT NOT NULL, memory_clock BIGINT NOT NULL, memory_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5A729397975B7E7 ON vga_bios (model_id)');
        $this->addSql('ALTER TABLE graphic_card_models ADD CONSTRAINT FK_B3A750F65278319C FOREIGN KEY (series_id) REFERENCES graphic_card_series (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE graphic_card_models ADD CONSTRAINT FK_B3A750F6F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE graphic_card_series ADD CONSTRAINT FK_6D6161D2F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vga_bios ADD CONSTRAINT FK_C5A729397975B7E7 FOREIGN KEY (model_id) REFERENCES graphic_card_models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vendor ADD pci_sig_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52233F64C71CB8A ON vendor (pci_sig_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vga_bios DROP CONSTRAINT FK_C5A729397975B7E7');
        $this->addSql('ALTER TABLE graphic_card_models DROP CONSTRAINT FK_B3A750F65278319C');
        $this->addSql('DROP SEQUENCE graphic_card_models_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE graphic_card_series_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE urls_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vendor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vga_bios_id_seq CASCADE');
        $this->addSql('DROP TABLE graphic_card_models');
        $this->addSql('DROP TABLE graphic_card_series');
        $this->addSql('DROP TABLE urls');
        $this->addSql('DROP TABLE vga_bios');
        $this->addSql('DROP INDEX UNIQ_F52233F64C71CB8A');
        $this->addSql('ALTER TABLE vendor DROP pci_sig_id');
    }
}
