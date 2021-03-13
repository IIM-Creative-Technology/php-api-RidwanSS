<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313124703 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere ADD nomintervenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A8894EDA6 FOREIGN KEY (nomintervenant_id) REFERENCES intervenant (id)');
        $this->addSql('CREATE INDEX IDX_9014574A8894EDA6 ON matiere (nomintervenant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A8894EDA6');
        $this->addSql('DROP INDEX IDX_9014574A8894EDA6 ON matiere');
        $this->addSql('ALTER TABLE matiere DROP nomintervenant_id');
    }
}
