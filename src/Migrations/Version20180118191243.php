<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118191243 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho ADD codigo_ciudad_origen_fk VARCHAR(20) DEFAULT NULL, ADD codigo_ciudad_destino_fk VARCHAR(20) DEFAULT NULL, ADD numero DOUBLE PRECISION DEFAULT NULL, ADD manifiesto DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE despacho ADD CONSTRAINT FK_254BF5B37A00F7C1 FOREIGN KEY (codigo_ciudad_origen_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE despacho ADD CONSTRAINT FK_254BF5B3768A42FB FOREIGN KEY (codigo_ciudad_destino_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('CREATE INDEX IDX_254BF5B37A00F7C1 ON despacho (codigo_ciudad_origen_fk)');
        $this->addSql('CREATE INDEX IDX_254BF5B3768A42FB ON despacho (codigo_ciudad_destino_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE despacho DROP FOREIGN KEY FK_254BF5B37A00F7C1');
        $this->addSql('ALTER TABLE despacho DROP FOREIGN KEY FK_254BF5B3768A42FB');
        $this->addSql('DROP INDEX IDX_254BF5B37A00F7C1 ON despacho');
        $this->addSql('DROP INDEX IDX_254BF5B3768A42FB ON despacho');
        $this->addSql('ALTER TABLE despacho DROP codigo_ciudad_origen_fk, DROP codigo_ciudad_destino_fk, DROP numero, DROP manifiesto');
    }
}
