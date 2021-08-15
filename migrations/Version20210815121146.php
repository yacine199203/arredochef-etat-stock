<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210815121146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire ADD traitepar_id INT NOT NULL, ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E032E01446 FOREIGN KEY (traitepar_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_338920E032E01446 ON inventaire (traitepar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E032E01446');
        $this->addSql('DROP INDEX IDX_338920E032E01446 ON inventaire');
        $this->addSql('ALTER TABLE inventaire DROP traitepar_id, DROP slug');
    }
}
