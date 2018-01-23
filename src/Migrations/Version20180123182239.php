<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123182239 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ruta_recogida ADD codigo_operacion_fk VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE ruta_recogida ADD CONSTRAINT FK_CC2A118E401A89CA FOREIGN KEY (codigo_operacion_fk) REFERENCES operacion (codigo_operacion_pk)');
        $this->addSql('CREATE INDEX IDX_CC2A118E401A89CA ON ruta_recogida (codigo_operacion_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ruta_recogida DROP FOREIGN KEY FK_CC2A118E401A89CA');
        $this->addSql('DROP INDEX IDX_CC2A118E401A89CA ON ruta_recogida');
        $this->addSql('ALTER TABLE ruta_recogida DROP codigo_operacion_fk');
    }
}
