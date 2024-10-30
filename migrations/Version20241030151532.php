<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030151532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_XY ON gridcell (x_id, y_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NAME ON gridcol (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_MASTERKEY ON gridrow (masterkey)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_XY ON gridcell');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NAME ON gridcol');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_MASTERKEY ON gridrow');
    }
}
