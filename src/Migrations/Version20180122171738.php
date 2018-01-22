<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122171738 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vehiculo (codigo_vehiculo_pk VARCHAR(20) NOT NULL, placa VARCHAR(10) DEFAULT NULL, placa_remolque VARCHAR(10) DEFAULT NULL, modelo INT DEFAULT NULL, modelo_repotenciado INT DEFAULT NULL, motor VARCHAR(50) DEFAULT NULL, numero_ejes INT DEFAULT NULL, chasis VARCHAR(50) DEFAULT NULL, serie VARCHAR(50) DEFAULT NULL, peso_vacio INT DEFAULT NULL, capacidad INT DEFAULT NULL, celular VARCHAR(30) DEFAULT NULL, registro_nacional_carga VARCHAR(50) DEFAULT NULL, PRIMARY KEY(codigo_vehiculo_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ruta_recogida (codigo_ruta_recogida_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_ruta_recogida_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE despacho_recogida (codigo_despacho_recogida_pk INT AUTO_INCREMENT NOT NULL, fecha DATETIME DEFAULT NULL, PRIMARY KEY(codigo_despacho_recogida_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vehiculo');
        $this->addSql('DROP TABLE ruta_recogida');
        $this->addSql('DROP TABLE despacho_recogida');
    }
}
