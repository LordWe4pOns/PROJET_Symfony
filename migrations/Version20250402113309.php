<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402113309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_user AS SELECT id, country_id, login, roles, password, name, surname, birthday FROM l3_user');
        $this->addSql('DROP TABLE l3_user');
        $this->addSql('CREATE TABLE l3_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country_id INTEGER DEFAULT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, CONSTRAINT FK_4500DDA3F92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_user (id, country_id, login, roles, password, name, surname, birthday) SELECT id, country_id, login, roles, password, name, surname, birthday FROM __temp__l3_user');
        $this->addSql('DROP TABLE __temp__l3_user');
        $this->addSql('CREATE INDEX IDX_4500DDA3F92F3E70 ON l3_user (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON l3_user (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_user AS SELECT id, country_id, login, roles, password, name, surname, birthday FROM l3_user');
        $this->addSql('DROP TABLE l3_user');
        $this->addSql('CREATE TABLE l3_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country_id INTEGER NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, CONSTRAINT FK_4500DDA3F92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_user (id, country_id, login, roles, password, name, surname, birthday) SELECT id, country_id, login, roles, password, name, surname, birthday FROM __temp__l3_user');
        $this->addSql('DROP TABLE __temp__l3_user');
        $this->addSql('CREATE INDEX IDX_4500DDA3F92F3E70 ON l3_user (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON l3_user (login)');
    }
}
