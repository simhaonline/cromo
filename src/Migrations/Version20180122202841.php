<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122202841 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE color (codigo_color_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_color_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aseguradora (codigo_aseguradora_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_aseguradora_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marca (codigo_marca_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_marca_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehiculo ADD configuracion VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE aseguradora');
        $this->addSql('DROP TABLE marca');
        $this->addSql('ALTER TABLE vehiculo DROP configuracion');
    }
}
