<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181004095445 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD fullname LONGTEXT NOT NULL, ADD username VARCHAR(255) NOT NULL, ADD email LONGTEXT NOT NULL, ADD roles VARCHAR(255) NOT NULL, DROP nom, DROP pseudo, DROP mail, DROP rang');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD nom LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD pseudo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD mail LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD rang VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP fullname, DROP username, DROP email, DROP roles');
    }
}
