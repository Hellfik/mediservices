<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191005151107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0911B61704B');
        $this->addSql('DROP INDEX IDX_18D2B0911B61704B ON materiel');
        $this->addSql('ALTER TABLE materiel DROP pret_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE materiel ADD pret_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0911B61704B FOREIGN KEY (pret_id) REFERENCES pret (id)');
        $this->addSql('CREATE INDEX IDX_18D2B0911B61704B ON materiel (pret_id)');
    }
}
