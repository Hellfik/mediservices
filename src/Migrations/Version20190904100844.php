<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904100844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE materiel ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09112469DE2 FOREIGN KEY (category_id) REFERENCES category_materiel (id)');
        $this->addSql('CREATE INDEX IDX_18D2B09112469DE2 ON materiel (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09112469DE2');
        $this->addSql('DROP INDEX IDX_18D2B09112469DE2 ON materiel');
        $this->addSql('ALTER TABLE materiel DROP category_id');
    }
}
