<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181015102016 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cartes ADD rang INT NOT NULL, ADD qte INT NOT NULL, DROP carte_rang, DROP carte_qte, CHANGE carte_img img LONGTEXT NOT NULL, CHANGE carte_nom nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cartes ADD carte_rang INT NOT NULL, ADD carte_qte INT NOT NULL, DROP rang, DROP qte, CHANGE img carte_img LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nom carte_nom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
