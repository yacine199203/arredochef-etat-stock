<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210816121806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE inventaire_list_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventaire_list_product (inventaire_list_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_6C9EAD99DAB56C4D (inventaire_list_id), INDEX IDX_6C9EAD994584665A (product_id), PRIMARY KEY(inventaire_list_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inventaire_list_product ADD CONSTRAINT FK_6C9EAD994584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventaire_list_product ADD CONSTRAINT FK_6C9EAD99DAB56C4D FOREIGN KEY (inventaire_list_id) REFERENCES inventaire_list (id) ON DELETE CASCADE');
    }
}
