<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118201043 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia ADD codigo_despacho_fk INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guia ADD CONSTRAINT FK_5B053B3DDD4D7AD9 FOREIGN KEY (codigo_despacho_fk) REFERENCES despacho (codigo_despacho_pk)');
        $this->addSql('CREATE INDEX IDX_5B053B3DDD4D7AD9 ON guia (codigo_despacho_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guia DROP FOREIGN KEY FK_5B053B3DDD4D7AD9');
        $this->addSql('DROP INDEX IDX_5B053B3DDD4D7AD9 ON guia');
        $this->addSql('ALTER TABLE guia DROP codigo_despacho_fk');
    }
}
