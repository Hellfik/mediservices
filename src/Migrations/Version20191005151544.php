<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191005151544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pret_materiel (pret_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_73CBDBAA1B61704B (pret_id), INDEX IDX_73CBDBAA16880AAF (materiel_id), PRIMARY KEY(pret_id, materiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pret_materiel ADD CONSTRAINT FK_73CBDBAA1B61704B FOREIGN KEY (pret_id) REFERENCES pret (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pret_materiel ADD CONSTRAINT FK_73CBDBAA16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pret_materiel');
    }
}
