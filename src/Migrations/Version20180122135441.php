<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122135441 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D644AA6FE7');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D65C2C6687');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D6768A42FB');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D67A00F7C1');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D6C412D806');
        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D6DD4D7AD9');
        $this->addSql('DROP INDEX IDX_1D0877D644AA6FE7 ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D6C412D806 ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D65C2C6687 ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D67A00F7C1 ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D6768A42FB ON recogida');
        $this->addSql('DROP INDEX IDX_1D0877D6DD4D7AD9 ON recogida');
        $this->addSql('ALTER TABLE recogida DROP codigo_sede_ingreso_fk, DROP codigo_sede_cargo_fk, DROP codigo_cliente_fk, DROP codigo_ciudad_origen_fk, DROP codigo_ciudad_destino_fk, DROP codigo_despacho_fk, DROP numero, DROP documentoCliente, DROP remitente, DROP nombre_destinatario, DROP direccion_destinatario, DROP telefono_destinatario, DROP fecha_ingreso, DROP fecha_despacho, DROP fecha_entrega, DROP fecha_cumplido, DROP fecha_soporte, DROP unidades, DROP peso_real, DROP peso_volumen, DROP peso_facturado, DROP vr_declara, DROP vr_flete, DROP vr_manejo, DROP vr_recaudo, DROP estado_impreso, DROP estado_despachado, DROP estado_entregado, DROP estado_soporte, DROP estado_cumplido, DROP comentario');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recogida ADD codigo_sede_ingreso_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, ADD codigo_sede_cargo_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, ADD codigo_cliente_fk INT DEFAULT NULL, ADD codigo_ciudad_origen_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, ADD codigo_ciudad_destino_fk VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, ADD codigo_despacho_fk INT DEFAULT NULL, ADD numero DOUBLE PRECISION DEFAULT NULL, ADD documentoCliente VARCHAR(80) DEFAULT NULL COLLATE utf8_unicode_ci, ADD remitente VARCHAR(80) DEFAULT NULL COLLATE utf8_unicode_ci, ADD nombre_destinatario VARCHAR(80) DEFAULT NULL COLLATE utf8_unicode_ci, ADD direccion_destinatario VARCHAR(80) DEFAULT NULL COLLATE utf8_unicode_ci, ADD telefono_destinatario VARCHAR(80) DEFAULT NULL COLLATE utf8_unicode_ci, ADD fecha_ingreso DATETIME DEFAULT NULL, ADD fecha_despacho DATETIME DEFAULT NULL, ADD fecha_entrega DATETIME DEFAULT NULL, ADD fecha_cumplido DATETIME DEFAULT NULL, ADD fecha_soporte DATETIME DEFAULT NULL, ADD unidades DOUBLE PRECISION NOT NULL, ADD peso_real DOUBLE PRECISION NOT NULL, ADD peso_volumen DOUBLE PRECISION NOT NULL, ADD peso_facturado DOUBLE PRECISION NOT NULL, ADD vr_declara DOUBLE PRECISION NOT NULL, ADD vr_flete DOUBLE PRECISION NOT NULL, ADD vr_manejo DOUBLE PRECISION NOT NULL, ADD vr_recaudo DOUBLE PRECISION NOT NULL, ADD estado_impreso TINYINT(1) DEFAULT NULL, ADD estado_despachado TINYINT(1) DEFAULT NULL, ADD estado_entregado TINYINT(1) DEFAULT NULL, ADD estado_soporte TINYINT(1) DEFAULT NULL, ADD estado_cumplido TINYINT(1) DEFAULT NULL, ADD comentario VARCHAR(2000) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D644AA6FE7 FOREIGN KEY (codigo_sede_ingreso_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D65C2C6687 FOREIGN KEY (codigo_cliente_fk) REFERENCES cliente (codigo_cliente_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6768A42FB FOREIGN KEY (codigo_ciudad_destino_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D67A00F7C1 FOREIGN KEY (codigo_ciudad_origen_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6C412D806 FOREIGN KEY (codigo_sede_cargo_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6DD4D7AD9 FOREIGN KEY (codigo_despacho_fk) REFERENCES despacho (codigo_despacho_pk)');
        $this->addSql('CREATE INDEX IDX_1D0877D644AA6FE7 ON recogida (codigo_sede_ingreso_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D6C412D806 ON recogida (codigo_sede_cargo_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D65C2C6687 ON recogida (codigo_cliente_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D67A00F7C1 ON recogida (codigo_ciudad_origen_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D6768A42FB ON recogida (codigo_ciudad_destino_fk)');
        $this->addSql('CREATE INDEX IDX_1D0877D6DD4D7AD9 ON recogida (codigo_despacho_fk)');
    }
}
