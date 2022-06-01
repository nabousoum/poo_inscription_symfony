<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601084114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription ADD ac_id INT DEFAULT NULL, ADD etudiant_id INT DEFAULT NULL, ADD annee_id INT DEFAULT NULL, ADD etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6D2E3ED2F FOREIGN KEY (ac_id) REFERENCES ac (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee_scolaire (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6D2E3ED2F ON inscription (ac_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6DDEAB1A3 ON inscription (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6543EC5F0 ON inscription (annee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6D2E3ED2F');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6DDEAB1A3');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6543EC5F0');
        $this->addSql('DROP INDEX IDX_5E90F6D6D2E3ED2F ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D6DDEAB1A3 ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D6543EC5F0 ON inscription');
        $this->addSql('ALTER TABLE inscription DROP ac_id, DROP etudiant_id, DROP annee_id, DROP etat');
    }
}
