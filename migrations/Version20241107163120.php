<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107163120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_SCOPECOLTYPE ON gridsetting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SCOPECOLKEY ON gridsetting (scope_id, gridcol_id, setting_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_SCOPECOLKEY ON gridsetting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SCOPECOLTYPE ON gridsetting (scope_id, gridcol_id)');
    }
}
