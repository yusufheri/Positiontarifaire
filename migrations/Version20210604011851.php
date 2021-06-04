<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604011851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_D34A04ADA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, created_at DATETIME NOT NULL, libelle CLOB NOT NULL COLLATE BINARY, tva VARCHAR(255) DEFAULT NULL COLLATE BINARY, ddi VARCHAR(255) DEFAULT NULL COLLATE BINARY, position VARCHAR(255) NOT NULL COLLATE BINARY, unite VARCHAR(255) DEFAULT NULL COLLATE BINARY, deleted_at DATETIME DEFAULT NULL, debut VARCHAR(255) NOT NULL, CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at) SELECT id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_D34A04ADA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, created_at DATETIME NOT NULL, libelle CLOB NOT NULL, tva VARCHAR(255) DEFAULT NULL, ddi VARCHAR(255) DEFAULT NULL, position VARCHAR(255) NOT NULL, unite VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, debut DATE NOT NULL)');
        $this->addSql('INSERT INTO product (id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at) SELECT id, user_id, created_at, libelle, tva, ddi, position, debut, unite, deleted_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
    }
}
