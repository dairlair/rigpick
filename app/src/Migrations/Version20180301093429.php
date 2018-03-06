<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180301093429 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE MATERIALIZED VIEW "vga_bios_index" AS
            SELECT
              a.id as id,
              COALESCE(v_b.brand, v_b.name) as model_vendor_name,
              v_c.name as series_vendor_name,
              b.name as model_name,
              c.name as series_name,
              a.memory_clock,
              a.memory_size,
              a.memory_type,
              b.vendor_id as model_vendor_id,
              c.vendor_id as series_vendor_id,
              a.model_id,
              b.series_id
            FROM
              vga_bios AS a
              INNER JOIN graphic_card_models AS b ON (a.model_id = b.id)
              INNER JOIN graphic_card_series AS c ON (b.series_id = c.id)
              INNER JOIN vendor v_b on b.vendor_id = v_b.id
              INNER JOIN vendor v_c on c.vendor_id = v_c.id
        ');

        $this->addSql('
            CREATE UNIQUE INDEX vga_bios_index_unique_id_idx on vga_bios_index (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP MATERIALIZED VIEW "vga_bios_index"');
    }
}
