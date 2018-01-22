<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122210516 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho_recogida ADD codigo_ruta_recogida_fk VARCHAR(20) DEFAULT NULL, ADD estado_descargado TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE despacho_recogida ADD CONSTRAINT FK_E4C761C679CE3146 FOREIGN KEY (codigo_ruta_recogida_fk) REFERENCES ruta_recogida (codigo_ruta_recogida_pk)');
        $this->addSql('CREATE INDEX IDX_E4C761C679CE3146 ON despacho_recogida (codigo_ruta_recogida_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho_recogida DROP FOREIGN KEY FK_E4C761C679CE3146');
        $this->addSql('DROP INDEX IDX_E4C761C679CE3146 ON despacho_recogida');
        $this->addSql('ALTER TABLE despacho_recogida DROP codigo_ruta_recogida_fk, DROP estado_descargado');
    }
}
