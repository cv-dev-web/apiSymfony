<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518082856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE related_user (id INT AUTO_INCREMENT NOT NULL, user_one_id INT DEFAULT NULL, user_two_id INT DEFAULT NULL, id_relation_user INT DEFAULT NULL, INDEX IDX_6681724C9EC8D52E (user_one_id), INDEX IDX_6681724CF59432E1 (user_two_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE related_user ADD CONSTRAINT FK_6681724C9EC8D52E FOREIGN KEY (user_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE related_user ADD CONSTRAINT FK_6681724CF59432E1 FOREIGN KEY (user_two_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE related_user');
    }
}
