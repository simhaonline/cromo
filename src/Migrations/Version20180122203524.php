<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122203524 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho_recogida ADD codigo_operacion_fk VARCHAR(20) DEFAULT NULL, ADD unidades DOUBLE PRECISION NOT NULL, ADD peso_real DOUBLE PRECISION NOT NULL, ADD peso_volumen DOUBLE PRECISION NOT NULL, ADD vr_declara DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE despacho_recogida ADD CONSTRAINT FK_E4C761C6401A89CA FOREIGN KEY (codigo_operacion_fk) REFERENCES operacion (codigo_operacion_pk)');
        $this->addSql('CREATE INDEX IDX_E4C761C6401A89CA ON despacho_recogida (codigo_operacion_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho_recogida DROP FOREIGN KEY FK_E4C761C6401A89CA');
        $this->addSql('DROP INDEX IDX_E4C761C6401A89CA ON despacho_recogida');
        $this->addSql('ALTER TABLE despacho_recogida DROP codigo_operacion_fk, DROP unidades, DROP peso_real, DROP peso_volumen, DROP vr_declara');
    }
}
