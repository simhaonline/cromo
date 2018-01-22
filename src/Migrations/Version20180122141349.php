<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122141349 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recogida ADD codigo_operacion_fk VARCHAR(20) DEFAULT NULL, ADD codigo_ciudad_fk VARCHAR(20) DEFAULT NULL, ADD codigo_ciudad_destino_fk VARCHAR(20) DEFAULT NULL, ADD fecha_registro DATETIME DEFAULT NULL, ADD fecha DATETIME DEFAULT NULL, ADD fecha_efectiva DATETIME DEFAULT NULL, ADD anunciante VARCHAR(200) DEFAULT NULL, ADD direccion VARCHAR(200) DEFAULT NULL, ADD telefono VARCHAR(50) DEFAULT NULL, ADD unidades DOUBLE PRECISION NOT NULL, ADD peso_real DOUBLE PRECISION NOT NULL, ADD peso_volumen DOUBLE PRECISION NOT NULL, ADD peso_facturado DOUBLE PRECISION NOT NULL, ADD vr_declara DOUBLE PRECISION NOT NULL, ADD estado_programado TINYINT(1) DEFAULT NULL, ADD estado_recogido TINYINT(1) DEFAULT NULL, ADD comentario VARCHAR(2000) DEFAULT NULL');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6401A89CA FOREIGN KEY (codigo_operacion_fk) REFERENCES operacion (codigo_operacion_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D638248982 FOREIGN KEY (codigo_ciudad_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6768A42FB FOREIGN KEY (codigo_ciudad_destino_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('CREATE INDEX IDX_1D0877D6401A89CA ON recogida (codigo_operacion_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D638248982 ON recogida (codigo_ciudad_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D6768A42FB ON recogida (codigo_ciudad_destino_fk)');
        $this->addSql('ALTER TABLE guia DROP codigo_sede_ingreso_fk, DROP codigo_sede_cargo_fk');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia ADD codigo_sede_ingreso_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, ADD codigo_sede_cargo_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D6401A89CA');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D638248982');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D6768A42FB');
        $this->addSql('DROP INDEX IDX_1D0877D6401A89CA ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D638248982 ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D6768A42FB ON recogida');
        $this->addSql('ALTER TABLE recogida DROP codigo_operacion_fk, DROP codigo_ciudad_fk, DROP codigo_ciudad_destino_fk, DROP fecha_registro, DROP fecha, DROP fecha_efectiva, DROP anunciante, DROP direccion, DROP telefono, DROP unidades, DROP peso_real, DROP peso_volumen, DROP peso_facturado, DROP vr_declara, DROP estado_programado, DROP estado_recogido, DROP comentario');
    }
}
