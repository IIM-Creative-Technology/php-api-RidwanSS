<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311191506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nomcours VARCHAR(255) NOT NULL, datestart DATE NOT NULL, dateend VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E35107904A');
        $this->addSql('DROP INDEX IDX_717E22E35107904A ON etudiant');
        $this->addSql('ALTER TABLE etudiant DROP nompromo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE matiere');
        $this->addSql('ALTER TABLE etudiant ADD nompromo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E35107904A FOREIGN KEY (nompromo_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_717E22E35107904A ON etudiant (nompromo_id)');
    }
}