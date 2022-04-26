<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426093459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content ADD resource_id INT NOT NULL, ADD chemin VARCHAR(255) DEFAULT NULL, DROP image, DROP video, DROP text');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A989329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A989329D25 ON content (resource_id)');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F41684A0A3ED');
        $this->addSql('DROP INDEX UNIQ_BC91F41684A0A3ED ON resource');
        $this->addSql('ALTER TABLE resource ADD text LONGTEXT NOT NULL, DROP content_id');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(10) NOT NULL, ADD user_creation_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A989329D25');
        $this->addSql('DROP INDEX IDX_FEC530A989329D25 ON content');
        $this->addSql('ALTER TABLE content ADD video VARCHAR(255) DEFAULT NULL, ADD text LONGTEXT DEFAULT NULL, DROP resource_id, CHANGE chemin image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD content_id INT NOT NULL, DROP text');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F41684A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC91F41684A0A3ED ON resource (content_id)');
        $this->addSql('ALTER TABLE user DROP phone, DROP user_creation_date');
    }
}
