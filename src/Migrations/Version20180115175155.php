<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115175155 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cliente (codigo_cliente_pk INT AUTO_INCREMENT NOT NULL, nombre_corto VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_cliente_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guia ADD codigo_cliente_fk INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D5C2C6687 FOREIGN KEY (codigo_cliente_fk) REFERENCES cliente (codigo_cliente_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D5C2C6687 ON guia (codigo_cliente_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D5C2C6687');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP INDEX IDX_5B053B3D5C2C6687 ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_cliente_fk');
    }
}
