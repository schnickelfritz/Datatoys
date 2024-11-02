<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102120520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridrow DROP INDEX UNIQ_IDENTIFIER_TABLELINENUMBER, ADD INDEX IDX_CE805B7E24F1A0FD (gridtable_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_TABLE_LINENUMBER ON gridrow (gridtable_id, line_number)');
        $this->addSql('ALTER TABLE workday RENAME INDEX uniq_identifier_emailname TO UNIQ_IDENTIFIER_DAYUSER');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridrow DROP INDEX IDX_CE805B7E24F1A0FD, ADD UNIQUE INDEX UNIQ_IDENTIFIER_TABLELINENUMBER (gridtable_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_TABLE_LINENUMBER ON gridrow');
        $this->addSql('ALTER TABLE workday RENAME INDEX uniq_identifier_dayuser TO UNIQ_IDENTIFIER_EMAILNAME');
    }
}
