<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115174210 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia ADD codigo_ciudad_destino_fk VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D768A42FB FOREIGN KEY (codigo_ciudad_destino_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D768A42FB ON guia (codigo_ciudad_destino_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D768A42FB');
        $this->addSql('DROP INDEX IDX_5B053B3D768A42FB ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_ciudad_destino_fk');
    }
}
