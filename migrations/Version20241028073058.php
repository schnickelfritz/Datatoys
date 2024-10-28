<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028073058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE workday (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_hour INT DEFAULT NULL, is_away TINYINT(1) NOT NULL, is_homeoffice TINYINT(1) NOT NULL, day DATE NOT NULL, work_hours INT DEFAULT NULL, INDEX IDX_38D5BF7BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workday ADD CONSTRAINT FK_38D5BF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workday DROP FOREIGN KEY FK_38D5BF7BA76ED395');
        $this->addSql('DROP TABLE workday');
    }
}
