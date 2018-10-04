<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180925083221 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parties ADD joueur1_id INT NOT NULL, ADD joueur2_id INT NOT NULL');
        $this->addSql('ALTER TABLE parties ADD CONSTRAINT FK_4363180592C1E237 FOREIGN KEY (joueur1_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE parties ADD CONSTRAINT FK_4363180580744DD9 FOREIGN KEY (joueur2_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_4363180592C1E237 ON parties (joueur1_id)');
        $this->addSql('CREATE INDEX IDX_4363180580744DD9 ON parties (joueur2_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parties DROP FOREIGN KEY FK_4363180592C1E237');
        $this->addSql('ALTER TABLE parties DROP FOREIGN KEY FK_4363180580744DD9');
        $this->addSql('DROP INDEX IDX_4363180592C1E237 ON parties');
        $this->addSql('DROP INDEX IDX_4363180580744DD9 ON parties');
        $this->addSql('ALTER TABLE parties DROP joueur1_id, DROP joueur2_id');
    }
}
