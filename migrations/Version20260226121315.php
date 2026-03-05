<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226121315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `keys` ADD games_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `keys` ADD CONSTRAINT FK_B48E44EC97FFC673 FOREIGN KEY (games_id) REFERENCES games (id)');
        $this->addSql('CREATE INDEX IDX_B48E44EC97FFC673 ON `keys` (games_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `keys` DROP FOREIGN KEY FK_B48E44EC97FFC673');
        $this->addSql('DROP INDEX IDX_B48E44EC97FFC673 ON `keys`');
        $this->addSql('ALTER TABLE `keys` DROP games_id');
    }
}
