<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518083822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, relation_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_resource (relation_id INT NOT NULL, resource_id INT NOT NULL, INDEX IDX_9B8ACEAE3256915B (relation_id), INDEX IDX_9B8ACEAE89329D25 (resource_id), PRIMARY KEY(relation_id, resource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relation_resource ADD CONSTRAINT FK_9B8ACEAE3256915B FOREIGN KEY (relation_id) REFERENCES relation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE relation_resource ADD CONSTRAINT FK_9B8ACEAE89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relation_resource DROP FOREIGN KEY FK_9B8ACEAE3256915B');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE relation_resource');
    }
}
