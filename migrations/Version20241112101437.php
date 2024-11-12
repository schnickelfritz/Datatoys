<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112101437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gridcell (id INT AUTO_INCREMENT NOT NULL, gridcol_id INT NOT NULL, gridrow_id INT NOT NULL, gridfile_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_6D716028C34B86BB (gridcol_id), INDEX IDX_6D7160288CD9871B (gridrow_id), INDEX IDX_6D7160284A12585E (gridfile_id), UNIQUE INDEX UNIQ_IDENTIFIER_ROWCOL (gridrow_id, gridcol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridcol (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridfile (id INT AUTO_INCREMENT NOT NULL, gridtable_id INT NOT NULL, original_name VARCHAR(255) NOT NULL, stored_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, filesize INT NOT NULL, INDEX IDX_2A69D1DA24F1A0FD (gridtable_id), UNIQUE INDEX UNIQ_IDENTIFIER_STOREDNAME (stored_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridrow (id INT AUTO_INCREMENT NOT NULL, gridtable_id INT NOT NULL, line_number INT NOT NULL, INDEX IDX_CE805B7E24F1A0FD (gridtable_id), UNIQUE INDEX UNIQ_IDENTIFIER_TABLE_LINENUMBER (gridtable_id, line_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridscope (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, scope_key VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAMEKEY (name, scope_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridscope_col (id INT AUTO_INCREMENT NOT NULL, scope_id INT NOT NULL, col_id INT NOT NULL, INDEX IDX_88CA2876682B5931 (scope_id), INDEX IDX_88CA2876CC306852 (col_id), UNIQUE INDEX UNIQ_IDENTIFIER_SCOPECOL (scope_id, col_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridsetting (id INT AUTO_INCREMENT NOT NULL, scope_id INT NOT NULL, gridcol_id INT NOT NULL, setting_key VARCHAR(30) NOT NULL, parameter VARCHAR(255) DEFAULT NULL, INDEX IDX_46AD99AA682B5931 (scope_id), INDEX IDX_46AD99AAC34B86BB (gridcol_id), UNIQUE INDEX UNIQ_IDENTIFIER_SCOPECOLKEY (scope_id, gridcol_id, setting_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gridtable (id INT AUTO_INCREMENT NOT NULL, scope_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(50) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, number_of_sources INT DEFAULT NULL, additional_expense INT DEFAULT NULL, INDEX IDX_8D3E520F682B5931 (scope_id), UNIQUE INDEX UNIQ_IDENTIFIER_NAMESCOPE (name, scope_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAILNAME (email, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_candidate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', invite_sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, setting_key VARCHAR(50) NOT NULL, setting_value LONGTEXT DEFAULT NULL, INDEX IDX_C779A692A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workday (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_hour INT DEFAULT NULL, is_away TINYINT(1) NOT NULL, is_homeoffice TINYINT(1) NOT NULL, day DATE NOT NULL, work_hours INT DEFAULT NULL, is_super_away TINYINT(1) DEFAULT NULL, INDEX IDX_38D5BF7BA76ED395 (user_id), UNIQUE INDEX UNIQ_IDENTIFIER_DAYUSER (day, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D716028C34B86BB FOREIGN KEY (gridcol_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D7160288CD9871B FOREIGN KEY (gridrow_id) REFERENCES gridrow (id)');
        $this->addSql('ALTER TABLE gridcell ADD CONSTRAINT FK_6D7160284A12585E FOREIGN KEY (gridfile_id) REFERENCES gridfile (id)');
        $this->addSql('ALTER TABLE gridfile ADD CONSTRAINT FK_2A69D1DA24F1A0FD FOREIGN KEY (gridtable_id) REFERENCES gridtable (id)');
        $this->addSql('ALTER TABLE gridrow ADD CONSTRAINT FK_CE805B7E24F1A0FD FOREIGN KEY (gridtable_id) REFERENCES gridtable (id)');
        $this->addSql('ALTER TABLE gridscope_col ADD CONSTRAINT FK_88CA2876682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id)');
        $this->addSql('ALTER TABLE gridscope_col ADD CONSTRAINT FK_88CA2876CC306852 FOREIGN KEY (col_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridsetting ADD CONSTRAINT FK_46AD99AA682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id)');
        $this->addSql('ALTER TABLE gridsetting ADD CONSTRAINT FK_46AD99AAC34B86BB FOREIGN KEY (gridcol_id) REFERENCES gridcol (id)');
        $this->addSql('ALTER TABLE gridtable ADD CONSTRAINT FK_8D3E520F682B5931 FOREIGN KEY (scope_id) REFERENCES gridscope (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workday ADD CONSTRAINT FK_38D5BF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D716028C34B86BB');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D7160288CD9871B');
        $this->addSql('ALTER TABLE gridcell DROP FOREIGN KEY FK_6D7160284A12585E');
        $this->addSql('ALTER TABLE gridfile DROP FOREIGN KEY FK_2A69D1DA24F1A0FD');
        $this->addSql('ALTER TABLE gridrow DROP FOREIGN KEY FK_CE805B7E24F1A0FD');
        $this->addSql('ALTER TABLE gridscope_col DROP FOREIGN KEY FK_88CA2876682B5931');
        $this->addSql('ALTER TABLE gridscope_col DROP FOREIGN KEY FK_88CA2876CC306852');
        $this->addSql('ALTER TABLE gridsetting DROP FOREIGN KEY FK_46AD99AA682B5931');
        $this->addSql('ALTER TABLE gridsetting DROP FOREIGN KEY FK_46AD99AAC34B86BB');
        $this->addSql('ALTER TABLE gridtable DROP FOREIGN KEY FK_8D3E520F682B5931');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395');
        $this->addSql('ALTER TABLE workday DROP FOREIGN KEY FK_38D5BF7BA76ED395');
        $this->addSql('DROP TABLE gridcell');
        $this->addSql('DROP TABLE gridcol');
        $this->addSql('DROP TABLE gridfile');
        $this->addSql('DROP TABLE gridrow');
        $this->addSql('DROP TABLE gridscope');
        $this->addSql('DROP TABLE gridscope_col');
        $this->addSql('DROP TABLE gridsetting');
        $this->addSql('DROP TABLE gridtable');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_candidate');
        $this->addSql('DROP TABLE user_setting');
        $this->addSql('DROP TABLE workday');
    }
}
