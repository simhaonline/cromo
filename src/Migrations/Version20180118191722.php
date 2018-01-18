<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118191722 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho ADD unidades DOUBLE PRECISION NOT NULL, ADD peso_real DOUBLE PRECISION NOT NULL, ADD peso_volumen DOUBLE PRECISION NOT NULL, ADD vr_declara DOUBLE PRECISION NOT NULL, ADD vr_flete DOUBLE PRECISION NOT NULL, ADD vr_manejo DOUBLE PRECISION NOT NULL, ADD vr_recaudo DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho DROP unidades, DROP peso_real, DROP peso_volumen, DROP vr_declara, DROP vr_flete, DROP vr_manejo, DROP vr_recaudo');
    }
}
