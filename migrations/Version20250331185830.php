<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331185830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE l3_booster_country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booster_id INTEGER NOT NULL, country_id INTEGER NOT NULL, CONSTRAINT FK_1F2D40ECF85E4930 FOREIGN KEY (booster_id) REFERENCES l3_booster (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1F2D40ECF92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1F2D40ECF85E4930 ON l3_booster_country (booster_id)');
        $this->addSql('CREATE INDEX IDX_1F2D40ECF92F3E70 ON l3_booster_country (country_id)');
        $this->addSql('DROP TABLE booster_country');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booster_country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booster_id INTEGER NOT NULL, country_id INTEGER NOT NULL, CONSTRAINT FK_780C6241F85E4930 FOREIGN KEY (booster_id) REFERENCES l3_booster (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_780C6241F92F3E70 FOREIGN KEY (country_id) REFERENCES l3_country (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_780C6241F92F3E70 ON booster_country (country_id)');
        $this->addSql('CREATE INDEX IDX_780C6241F85E4930 ON booster_country (booster_id)');
        $this->addSql('DROP TABLE l3_booster_country');
    }
}
