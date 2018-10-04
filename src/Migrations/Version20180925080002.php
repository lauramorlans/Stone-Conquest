<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180925080002 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE parties (id INT AUTO_INCREMENT NOT NULL, partie_date DATETIME NOT NULL, partie_statue VARCHAR(255) NOT NULL, partie_terrain LONGTEXT NOT NULL, partie_pioche LONGTEXT NOT NULL, jeton_chameaux INT NOT NULL, partie_defausse TINYINT(1) NOT NULL, main_j1 LONGTEXT NOT NULL, main_j2 LONGTEXT NOT NULL, chameaux_j1 INT NOT NULL, chameayx_j2 INT NOT NULL, jetons_j1 LONGTEXT NOT NULL, jetons_j2 LONGTEXT NOT NULL, jetons_victoire INT NOT NULL, jetons_terrain LONGTEXT NOT NULL, nb_manche INT NOT NULL, joueur_tour INT NOT NULL, point_j1 INT NOT NULL, point_j2 INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE parties');
    }
}
