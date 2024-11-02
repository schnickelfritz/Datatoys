<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102105852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D71602816CF4B4D');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D716028AE732C28');
        $this->addSql('DROP INDEX IDX_6D716028AE732C28 ON gridcell');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_XY ON gridcell');
        $this->addSql('DROP INDEX IDX_6D71602816CF4B4D ON gridcell');
        $this->addSql('ALTER TABLE gridcell ADD gridcol_id INT NOT NULL, ADD gridrow_id INT NOT NULL, DROP x_id, DROP y_id');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D716028C34B86BB FOREIGN KEY (gridcol_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D7160288CD9871B FOREIGN KEY (gridrow_id) REFERENCES gridrow (id)');
        $this->addSql('CREATE INDEX IDX_6D716028C34B86BB ON gridcell (gridcol_id)');
        $this->addSql('CREATE INDEX IDX_6D7160288CD9871B ON gridcell (gridrow_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ROWCOL ON gridcell (gridrow_id, gridcol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D716028C34B86BB');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D7160288CD9871B');
        $this->addSql('DROP INDEX IDX_6D716028C34B86BB ON gridcell');
        $this->addSql('DROP INDEX IDX_6D7160288CD9871B ON gridcell');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_ROWCOL ON gridcell');
        $this->addSql('ALTER TABLE gridcell ADD x_id INT NOT NULL, ADD y_id INT NOT NULL, DROP gridcol_id, DROP gridrow_id');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D71602816CF4B4D FOREIGN KEY (x_id) REFERENCES gridcol (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D716028AE732C28 FOREIGN KEY (y_id) REFERENCES gridrow (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6D716028AE732C28 ON gridcell (y_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_XY ON gridcell (x_id, y_id)');
        $this->addSql('CREATE INDEX IDX_6D71602816CF4B4D ON gridcell (x_id)');
    }
}
