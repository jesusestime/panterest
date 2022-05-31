<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531140623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4639883B4639883B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F9D86650F');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4639883B4639883B FOREIGN KEY (pin_id_id) REFERENCES pins (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F9D86650F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4639883B4639883B');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4639883B4639883B FOREIGN KEY (pin_id_id) REFERENCES pins (id) ON DELETE CASCADE');
    }
}
