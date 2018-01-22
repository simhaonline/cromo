<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122141922 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recogida ADD codigo_cliente_fk INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recogida ADD CONSTRAINT FK_1D0877D65C2C6687 FOREIGN KEY (codigo_cliente_fk) REFERENCES cliente (codigo_cliente_pk)');
        $this->addSql('CREATE INDEX IDX_1D0877D65C2C6687 ON recogida (codigo_cliente_fk)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recogida DROP FOREIGN KEY FK_1D0877D65C2C6687');
        $this->addSql('DROP INDEX IDX_1D0877D65C2C6687 ON recogida');
        $this->addSql('ALTER TABLE recogida DROP codigo_cliente_fk');
    }
}
