<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030145808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gridcell (id INT AUTO_INCREMENT NOT NULL, x_id INT NOT NULL, y_id INT NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_6D71602816CF4B4D (x_id), INDEX IDX_6D716028AE732C28 (y_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridrow (id INT AUTO_INCREMENT NOT NULL, pool_id INT NOT NULL, masterkey VARCHAR(255) DEFAULT NULL, INDEX IDX_CE805B7E7B3406DF (pool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D71602816CF4B4D FOREIGN KEY (x_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D716028AE732C28 FOREIGN KEY (y_id) REFERENCES gridrow (id)');
        $this->addSql('ALTER TABLE gridrow ADD CONSTRAINT FK_CE805B7E7B3406DF FOREIGN KEY (pool_id) REFERENCES gridpool (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D71602816CF4B4D');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D716028AE732C28');
        $this->addSql('ALTER TABLE gridrow DROP FOREIGN KEY FK_CE805B7E7B3406DF');
        $this->addSql('DROP TABLE gridcell');
        $this->addSql('DROP TABLE gridrow');
    }
}
