<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241101121504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, setting_key VARCHAR(50) NOT NULL, setting_value VARCHAR(255) DEFAULT NULL, INDEX IDX_C779A692A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gridrow DROP INDEX IDX_CE805B7E24F1A0FD, ADD UNIQUE INDEX UNIQ_IDENTIFIER_TABLELINENUMBER (gridtable_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_MASTERKEY ON gridrow');
        $this->addSql('ALTER TABLE gridrow ADD line_number INT NOT NULL, DROP masterkey');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395');
        $this->addSql('DROP TABLE user_setting');
        $this->addSql('ALTER TABLE gridrow DROP INDEX UNIQ_IDENTIFIER_TABLELINENUMBER, ADD INDEX IDX_CE805B7E24F1A0FD (gridtable_id)');
        $this->addSql('ALTER TABLE gridrow ADD masterkey VARCHAR(255) DEFAULT NULL, DROP line_number');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_MASTERKEY ON gridrow (masterkey)');
    }
}
