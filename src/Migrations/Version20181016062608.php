<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016062608 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jetons ADD rang INT NOT NULL, ADD qte INT NOT NULL, ADD score INT NOT NULL, DROP jeton_rang, DROP jeton_qte, DROP jeton_score, CHANGE jeton_img img LONGTEXT NOT NULL, CHANGE jeton_nom nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jetons ADD jeton_rang INT NOT NULL, ADD jeton_qte INT NOT NULL, ADD jeton_score INT NOT NULL, DROP rang, DROP qte, DROP score, CHANGE img jeton_img LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nom jeton_nom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
