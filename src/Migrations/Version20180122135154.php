<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122135154 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recogida (codigo_recogida_pk INT AUTO_INCREMENT NOT NULL, codigo_sede_ingreso_fk VARCHAR(20) DEFAULT NULL, codigo_sede_cargo_fk VARCHAR(20) DEFAULT NULL, codigo_cliente_fk INT DEFAULT NULL, codigo_ciudad_origen_fk VARCHAR(20) DEFAULT NULL, codigo_ciudad_destino_fk VARCHAR(20) DEFAULT NULL, codigo_despacho_fk INT DEFAULT NULL, numero DOUBLE PRECISION DEFAULT NULL, documentoCliente VARCHAR(80) DEFAULT NULL, remitente VARCHAR(80) DEFAULT NULL, nombre_destinatario VARCHAR(80) DEFAULT NULL, direccion_destinatario VARCHAR(80) DEFAULT NULL, telefono_destinatario VARCHAR(80) DEFAULT NULL, fecha_ingreso DATETIME DEFAULT NULL, fecha_despacho DATETIME DEFAULT NULL, fecha_entrega DATETIME DEFAULT NULL, fecha_cumplido DATETIME DEFAULT NULL, fecha_soporte DATETIME DEFAULT NULL, unidades DOUBLE PRECISION NOT NULL, peso_real DOUBLE PRECISION NOT NULL, peso_volumen DOUBLE PRECISION NOT NULL, peso_facturado DOUBLE PRECISION NOT NULL, vr_declara DOUBLE PRECISION NOT NULL, vr_flete DOUBLE PRECISION NOT NULL, vr_manejo DOUBLE PRECISION NOT NULL, vr_recaudo DOUBLE PRECISION NOT NULL, estado_impreso TINYINT(1) DEFAULT NULL, estado_despachado TINYINT(1) DEFAULT NULL, estado_entregado TINYINT(1) DEFAULT NULL, estado_soporte TINYINT(1) DEFAULT NULL, estado_cumplido TINYINT(1) DEFAULT NULL, comentario VARCHAR(2000) DEFAULT NULL, INDEX IDX_1D0877D644AA6FE7 (codigo_sede_ingreso_fk), INDEX IDX_1D0877D6C412D806 (codigo_sede_cargo_fk), INDEX IDX_1D0877D65C2C6687 (codigo_cliente_fk), INDEX IDX_1D0877D67A00F7C1 (codigo_ciudad_origen_fk), INDEX IDX_1D0877D6768A42FB (codigo_ciudad_destino_fk), INDEX IDX_1D0877D6DD4D7AD9 (codigo_despacho_fk), PRIMARY KEY(codigo_recogida_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operacion (codigo_operacion_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_operacion_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D644AA6FE7 FOREIGN KEY (codigo_sede_ingreso_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6C412D806 FOREIGN KEY (codigo_sede_cargo_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D65C2C6687 FOREIGN KEY (codigo_cliente_fk) REFERENCES cliente (codigo_cliente_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D67A00F7C1 FOREIGN KEY (codigo_ciudad_origen_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6768A42FB FOREIGN KEY (codigo_ciudad_destino_fk) REFERENCES ciudad (codigo_ciudad_pk)');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D6DD4D7AD9 FOREIGN KEY (codigo_despacho_fk) REFERENCES despacho (codigo_despacho_pk)');
        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D44AA6FE7');
        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3DC412D806');
        $this->addSql('DROP INDEX IDX_5B053B3D44AA6FE7 ON guia');
        $this->addSql('DROP INDEX IDX_5B053B3DC412D806 ON guia');
        $this->addSql('ALTER TABLE guia ADD codigo_operacion_ingreso_fk VARCHAR(20) DEFAULT NULL, ADD codigo_operacion_cargo_fk VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D7818DABE FOREIGN KEY (codigo_operacion_ingreso_fk) REFERENCES operacion (codigo_operacion_pk)');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D9E73D83D FOREIGN KEY (codigo_operacion_cargo_fk) REFERENCES operacion (codigo_operacion_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D7818DABE ON guia (codigo_operacion_ingreso_fk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D9E73D83D ON guia (codigo_operacion_cargo_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D7818DABE');
        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D9E73D83D');
        $this->addSql('DROP TABLE recogida');
        $this->addSql('DROP TABLE operacion');
        $this->addSql('DROP INDEX IDX_5B053B3D7818DABE ON guia');
        $this->addSql('DROP INDEX IDX_5B053B3D9E73D83D ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_operacion_ingreso_fk, DROP codigo_operacion_cargo_fk');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D44AA6FE7 FOREIGN KEY (codigo_sede_ingreso_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3DC412D806 FOREIGN KEY (codigo_sede_cargo_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D44AA6FE7 ON guia (codigo_sede_ingreso_fk)');
        $this->addSql('CREATE INDEX IDX_5B053B3DC412D806 ON guia (codigo_sede_cargo_fk)');
    }
}
