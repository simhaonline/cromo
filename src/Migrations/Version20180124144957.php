<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180124144957 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE despacho_recogida_auxiliar (codigo_despacho_recogida_auxiliar_pk INT AUTO_INCREMENT NOT NULL, codigo_despacho_recogida_fk INT DEFAULT NULL, codigo_auxiliar_fk INT DEFAULT NULL, PRIMARY KEY(codigo_despacho_recogida_auxiliar_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auxiliar (codigo_auxiliar_pk INT AUTO_INCREMENT NOT NULL, numero_identificacion VARCHAR(20) DEFAULT NULL, nombre_corto VARCHAR(150) DEFAULT NULL, PRIMARY KEY(codigo_auxiliar_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE despacho_recogida ADD vr_pago DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE despacho_recogida_auxiliar');
        $this->addSql('DROP TABLE auxiliar');
        $this->addSql('ALTER TABLE despacho_recogida DROP vr_pago');
    }
}
