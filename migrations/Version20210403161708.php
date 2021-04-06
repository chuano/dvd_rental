<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210403161708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_customers (id VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, first_surname VARCHAR(255) NOT NULL, second_surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_sales (id VARCHAR(255) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', movie_id VARCHAR(255) NOT NULL, customer_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, first_surname VARCHAR(255) NOT NULL, second_surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_users (id VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B4A95E13E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies (id VARCHAR(255) NOT NULL, stock INT NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, synopsis VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_orders (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, movie_id VARCHAR(255) NOT NULL, date_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_to DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_users (id VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, first_surname VARCHAR(255) NOT NULL, second_surname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8F682B06E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin_customers');
        $this->addSql('DROP TABLE admin_sales');
        $this->addSql('DROP TABLE admin_users');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE rental_orders');
        $this->addSql('DROP TABLE rental_users');
    }
}
