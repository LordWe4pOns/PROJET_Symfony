<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329152539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE l3_cart_content (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cart_id INTEGER NOT NULL, booster_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_579F520A1AD5CDBF FOREIGN KEY (cart_id) REFERENCES l3_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_579F520AF85E4930 FOREIGN KEY (booster_id) REFERENCES l3_booster (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_579F520A1AD5CDBF ON l3_cart_content (cart_id)');
        $this->addSql('CREATE INDEX IDX_579F520AF85E4930 ON l3_cart_content (booster_id)');
        $this->addSql('DROP TABLE cart_content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_content (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cart_id INTEGER NOT NULL, booster_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_51FF8AE1AD5CDBF FOREIGN KEY (cart_id) REFERENCES l3_cart (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_51FF8AEF85E4930 FOREIGN KEY (booster_id) REFERENCES l3_booster (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_51FF8AEF85E4930 ON cart_content (booster_id)');
        $this->addSql('CREATE INDEX IDX_51FF8AE1AD5CDBF ON cart_content (cart_id)');
        $this->addSql('DROP TABLE l3_cart_content');
    }
}
