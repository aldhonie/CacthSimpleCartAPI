<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200522031014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, sale_price, retail_price, image_url, quantity_available FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, retail_price INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, sale_price INTEGER NOT NULL, image_url VARCHAR(255) NOT NULL, quantity_available INTEGER NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, sale_price, retail_price, image_url, quantity_available) SELECT id, name, sale_price, retail_price, image_url, quantity_available FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, sale_price, retail_price, image_url, quantity_available FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id VARCHAR(255) NOT NULL COLLATE BINARY, retail_price INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, sale_price INTEGER DEFAULT NULL, image_url VARCHAR(500) DEFAULT NULL COLLATE BINARY, quantity_available INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO product (id, name, sale_price, retail_price, image_url, quantity_available) SELECT id, name, sale_price, retail_price, image_url, quantity_available FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }
}
