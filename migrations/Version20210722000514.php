<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722000514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colonne (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_product (id INT AUTO_INCREMENT NOT NULL, colonne_id INT NOT NULL, product_id INT NOT NULL, created_at DATETIME NOT NULL, name LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_B4858478213EAC9D (colonne_id), INDEX IDX_B48584784584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detail_product ADD CONSTRAINT FK_B4858478213EAC9D FOREIGN KEY (colonne_id) REFERENCES colonne (id)');
        $this->addSql('ALTER TABLE detail_product ADD CONSTRAINT FK_B48584784584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_product DROP FOREIGN KEY FK_B4858478213EAC9D');
        $this->addSql('DROP TABLE colonne');
        $this->addSql('DROP TABLE detail_product');
    }
}
