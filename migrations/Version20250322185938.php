<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250322185938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE l3_booster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, expansion_id INTEGER NOT NULL, country_id INTEGER NOT NULL, name VARCHAR(100) NOT NULL, price INTEGER NOT NULL, stock INTEGER NOT NULL, CONSTRAINT FK_C1F1D1265C15249D FOREIGN KEY (expansion_id) REFERENCES l3_expansion (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C1F1D126F92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C1F1D1265C15249D ON l3_booster (expansion_id)');
        $this->addSql('CREATE INDEX IDX_C1F1D126F92F3E70 ON l3_booster (country_id)');
        $this->addSql('CREATE TABLE l3_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_C330835DA76ED395 FOREIGN KEY (user_id) REFERENCES l3_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C330835DA76ED395 ON l3_cart (user_id)');
        $this->addSql('CREATE TABLE cart_booster (cart_id INTEGER NOT NULL, booster_id INTEGER NOT NULL, PRIMARY KEY(cart_id, booster_id), CONSTRAINT FK_14AC57AA1AD5CDBF FOREIGN KEY (cart_id) REFERENCES l3_cart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14AC57AAF85E4930 FOREIGN KEY (booster_id) REFERENCES l3_booster (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_14AC57AA1AD5CDBF ON cart_booster (cart_id)');
        $this->addSql('CREATE INDEX IDX_14AC57AAF85E4930 ON cart_booster (booster_id)');
        $this->addSql('CREATE TABLE l3_country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(2) NOT NULL)');
        $this->addSql('CREATE TABLE l3_expansion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, number VARCHAR(20) NOT NULL)');
        $this->addSql('CREATE TABLE l3_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country_id INTEGER NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, birthday DATE NOT NULL, CONSTRAINT FK_4500DDA3F92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4500DDA3F92F3E70 ON l3_user (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON l3_user (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE l3_booster');
        $this->addSql('DROP TABLE l3_cart');
        $this->addSql('DROP TABLE cart_booster');
        $this->addSql('DROP TABLE l3_country');
        $this->addSql('DROP TABLE l3_expansion');
        $this->addSql('DROP TABLE l3_user');
    }
}
