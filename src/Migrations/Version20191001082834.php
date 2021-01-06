<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191001082834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pret ADD commercial_id INT NOT NULL');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE9797854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_52ECE9797854071C ON pret (commercial_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE9797854071C');
        $this->addSql('DROP INDEX IDX_52ECE9797854071C ON pret');
        $this->addSql('ALTER TABLE pret DROP commercial_id');
    }
}
