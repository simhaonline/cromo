<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115173627 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad ADD nombre VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD codigo_ciudad_origen_fk VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D7A00F7C1 FOREIGN KEY (codigo_ciudad_origen_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D7A00F7C1 ON guia (codigo_ciudad_origen_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad DROP nombre');
        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D7A00F7C1');
        $this->addSql('DROP INDEX IDX_5B053B3D7A00F7C1 ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_ciudad_origen_fk');
    }
}
