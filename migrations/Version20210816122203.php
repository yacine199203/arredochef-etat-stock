<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210816122203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire_list ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventaire_list ADD CONSTRAINT FK_8B84A5DD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_8B84A5DD4584665A ON inventaire_list (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire_list DROP FOREIGN KEY FK_8B84A5DD4584665A');
        $this->addSql('DROP INDEX IDX_8B84A5DD4584665A ON inventaire_list');
        $this->addSql('ALTER TABLE inventaire_list DROP product_id');
    }
}
