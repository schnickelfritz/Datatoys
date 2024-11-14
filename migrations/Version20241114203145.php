<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114203145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coco_item (id INT AUTO_INCREMENT NOT NULL, coco_id VARCHAR(20) NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_COCOID (coco_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coco_ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, parent_ticket_id INT DEFAULT NULL, name VARCHAR(60) NOT NULL, category VARCHAR(60) DEFAULT NULL, created_at DATE NOT NULL, due_date DATE DEFAULT NULL, priority INT DEFAULT NULL, transfer_status VARCHAR(20) DEFAULT NULL, process_status VARCHAR(20) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, explanation LONGTEXT DEFAULT NULL, image_count INT DEFAULT NULL, item_count INT DEFAULT NULL, type VARCHAR(40) DEFAULT NULL, INDEX IDX_1D878400A76ED395 (user_id), INDEX IDX_1D878400814B683C (parent_ticket_id), UNIQUE INDEX UNIQ_IDENTIFIER_TICKETNAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coco_ticket_position (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, item_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, explanation VARCHAR(255) DEFAULT NULL, INDEX IDX_4D64EF8C700047D2 (ticket_id), INDEX IDX_4D64EF8C126F525E (item_id), UNIQUE INDEX UNIQ_IDENTIFIER_TICKETITEM (ticket_id, item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coco_ticket ADD CONSTRAINT FK_1D878400A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coco_ticket ADD CONSTRAINT FK_1D878400814B683C FOREIGN KEY (parent_ticket_id) REFERENCES coco_ticket (id)');
        $this->addSql('ALTER TABLE coco_ticket_position ADD CONSTRAINT FK_4D64EF8C700047D2 FOREIGN KEY (ticket_id) REFERENCES coco_ticket (id)');
        $this->addSql('ALTER TABLE coco_ticket_position ADD CONSTRAINT FK_4D64EF8C126F525E FOREIGN KEY (item_id) REFERENCES coco_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coco_ticket DROP FOREIGN KEY FK_1D878400A76ED395');
        $this->addSql('ALTER TABLE coco_ticket DROP FOREIGN KEY FK_1D878400814B683C');
        $this->addSql('ALTER TABLE coco_ticket_position DROP FOREIGN KEY FK_4D64EF8C700047D2');
        $this->addSql('ALTER TABLE coco_ticket_position DROP FOREIGN KEY FK_4D64EF8C126F525E');
        $this->addSql('DROP TABLE coco_item');
        $this->addSql('DROP TABLE coco_ticket');
        $this->addSql('DROP TABLE coco_ticket_position');
    }
}
