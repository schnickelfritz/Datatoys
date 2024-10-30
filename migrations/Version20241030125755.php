<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030125755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gridpool (id INT AUTO_INCREMENT NOT NULL, scope_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_9674E4C682B5931 (scope_id), UNIQUE INDEX UNIQ_IDENTIFIER_NAMESCOPE (name, scope_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gridpool ADD CONSTRAINT FK_9674E4C682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridpool DROP FOREIGN KEY FK_9674E4C682B5931');
        $this->addSql('DROP TABLE gridpool');
    }
}
