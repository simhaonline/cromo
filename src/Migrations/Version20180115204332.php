<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115204332 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sede (codigo_sede_pk VARCHAR(20) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(codigo_sede_pk)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guia ADD codigo_sede_ingreso_fk VARCHAR(20) DEFAULT NULL, ADD codigo_sede_cargo_fk VARCHAR(20) DEFAULT NULL, ADD documentoCliente VARCHAR(80) DEFAULT NULL, ADD remitente VARCHAR(80) DEFAULT NULL, ADD nombre_destinatario VARCHAR(80) DEFAULT NULL, ADD direccion_destinatario VARCHAR(80) DEFAULT NULL, ADD telefono_destinatario VARCHAR(80) DEFAULT NULL, ADD fecha_ingreso DATETIME DEFAULT NULL, ADD fecha_despacho DATETIME DEFAULT NULL, ADD fecha_entrega DATETIME DEFAULT NULL, ADD fecha_cumplido DATETIME DEFAULT NULL, ADD fecha_soporte DATETIME DEFAULT NULL, ADD unidades DOUBLE PRECISION NOT NULL, ADD peso_real DOUBLE PRECISION NOT NULL, ADD peso_volumen DOUBLE PRECISION NOT NULL, ADD peso_facturado DOUBLE PRECISION NOT NULL, ADD vr_declara DOUBLE PRECISION NOT NULL, ADD vr_flete DOUBLE PRECISION NOT NULL, ADD vr_manejo DOUBLE PRECISION NOT NULL, ADD vr_recaudo DOUBLE PRECISION NOT NULL, ADD estado_despachado TINYINT(1) DEFAULT NULL, ADD estado_entregado TINYINT(1) DEFAULT NULL, ADD estado_soporte TINYINT(1) DEFAULT NULL, ADD estado_cumplido TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3D44AA6FE7 FOREIGN KEY (codigo_sede_ingreso_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3DC412D806 FOREIGN KEY (codigo_sede_cargo_fk) REFERENCES sede (codigo_sede_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3D44AA6FE7 ON guia (codigo_sede_ingreso_fk)');
        $this->addSql('CREATE INDEX IDX_5B053B3DC412D806 ON guia (codigo_sede_cargo_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3D44AA6FE7');
        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3DC412D806');
        $this->addSql('DROP TABLE sede');
        $this->addSql('DROP INDEX IDX_5B053B3D44AA6FE7 ON guia');
        $this->addSql('DROP INDEX IDX_5B053B3DC412D806 ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_sede_ingreso_fk, DROP codigo_sede_cargo_fk, DROP documentoCliente, DROP remitente, DROP nombre_destinatario, DROP direccion_destinatario, DROP telefono_destinatario, DROP fecha_ingreso, DROP fecha_despacho, DROP fecha_entrega, DROP fecha_cumplido, DROP fecha_soporte, DROP unidades, DROP peso_real, DROP peso_volumen, DROP peso_facturado, DROP vr_declara, DROP vr_flete, DROP vr_manejo, DROP vr_recaudo, DROP estado_despachado, DROP estado_entregado, DROP estado_soporte, DROP estado_cumplido');
    }
}
