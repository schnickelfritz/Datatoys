<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241101084942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gridcell (id INT AUTO_INCREMENT NOT NULL, x_id INT NOT NULL, y_id INT NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_6D71602816CF4B4D (x_id), INDEX IDX_6D716028AE732C28 (y_id), UNIQUE INDEX UNIQ_IDENTIFIER_XY (x_id, y_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridcol (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridrow (id INT AUTO_INCREMENT NOT NULL, gridtable_id INT NOT NULL, masterkey VARCHAR(255) DEFAULT NULL, INDEX IDX_CE805B7E24F1A0FD (gridtable_id), UNIQUE INDEX UNIQ_IDENTIFIER_MASTERKEY (masterkey), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridscope (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, scope_key VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAMEKEY (name, scope_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridscope_col (id INT AUTO_INCREMENT NOT NULL, scope_id INT NOT NULL, col_id INT NOT NULL, INDEX IDX_88CA2876682B5931 (scope_id), INDEX IDX_88CA2876CC306852 (col_id), UNIQUE INDEX UNIQ_IDENTIFIER_SCOPECOL (scope_id, col_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridtable (id INT AUTO_INCREMENT NOT NULL, scope_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(50) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, number_of_sources INT DEFAULT NULL, additional_expense INT DEFAULT NULL, INDEX IDX_8D3E520F682B5931 (scope_id), UNIQUE INDEX UNIQ_IDENTIFIER_NAMESCOPE (name, scope_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAILNAME (email, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_candidate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', invite_sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workday (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_hour INT DEFAULT NULL, is_away TINYINT(1) NOT NULL, is_homeoffice TINYINT(1) NOT NULL, day DATE NOT NULL, work_hours INT DEFAULT NULL, is_super_away TINYINT(1) DEFAULT NULL, INDEX IDX_38D5BF7BA76ED395 (user_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAILNAME (day, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D71602816CF4B4D FOREIGN KEY (x_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D716028AE732C28 FOREIGN KEY (y_id) REFERENCES gridrow (id)');
        $this->addSql('ALTER TABLE gridrow ADD CONSTRAINT FK_CE805B7E24F1A0FD FOREIGN KEY (gridtable_id) REFERENCES gridtable (id)');
        $this->addSql('ALTER TABLE gridscope_col ADD CONSTRAINT FK_88CA2876682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id)');
        $this->addSql('ALTER TABLE gridscope_col ADD CONSTRAINT FK_88CA2876CC306852 FOREIGN KEY (col_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridtable ADD CONSTRAINT FK_8D3E520F682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE workday ADD CONSTRAINT FK_38D5BF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D71602816CF4B4D');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D716028AE732C28');
        $this->addSql('ALTER TABLE gridrow DROP FOREIGN KEY FK_CE805B7E24F1A0FD');
        $this->addSql('ALTER TABLE gridscope_col DROP FOREIGN KEY FK_88CA2876682B5931');
        $this->addSql('ALTER TABLE gridscope_col DROP FOREIGN KEY FK_88CA2876CC306852');
        $this->addSql('ALTER TABLE gridtable DROP FOREIGN KEY FK_8D3E520F682B5931');
        $this->addSql('ALTER TABLE workday DROP FOREIGN KEY FK_38D5BF7BA76ED395');
        $this->addSql('DROP TABLE gridcell');
        $this->addSql('DROP TABLE gridcol');
        $this->addSql('DROP TABLE gridrow');
        $this->addSql('DROP TABLE gridscope');
        $this->addSql('DROP TABLE gridscope_col');
        $this->addSql('DROP TABLE gridtable');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_candidate');
        $this->addSql('DROP TABLE workday');
    }
}
