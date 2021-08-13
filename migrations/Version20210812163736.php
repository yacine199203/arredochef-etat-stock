<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812163736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_depot (category_id INT NOT NULL, depot_id INT NOT NULL, INDEX IDX_AFA7C4F512469DE2 (category_id), INDEX IDX_AFA7C4F58510D4DE (depot_id), PRIMARY KEY(category_id, depot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_depot ADD CONSTRAINT FK_AFA7C4F512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_depot ADD CONSTRAINT FK_AFA7C4F58510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category_depot');
    }
}
