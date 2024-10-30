<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030144003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridpool DROP FOREIGN KEY FK_9674E4C682B5931');
        $this->addSql('ALTER TABLE gridpool ADD CONSTRAINT FK_9674E4C682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridpool DROP FOREIGN KEY FK_9674E4C682B5931');
        $this->addSql('ALTER TABLE gridpool ADD CONSTRAINT FK_9674E4C682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
