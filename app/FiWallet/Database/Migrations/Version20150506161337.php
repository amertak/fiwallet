<?php

namespace FiWallet\Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150506161337 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE filters');
        $this->addSql('CREATE TABLE filters_conditions (id INT AUTO_INCREMENT NOT NULL, filter_id INT DEFAULT NULL, property VARCHAR(255) NOT NULL, operator VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_F314BA79D395B25E (filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filters_conditions ADD CONSTRAINT FK_F314BA79D395B25E FOREIGN KEY (filter_id) REFERENCES filters (id)');
        $this->addSql('ALTER TABLE filters ADD name VARCHAR(255) NOT NULL, DROP data');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE filters_conditions');
        $this->addSql('ALTER TABLE filters ADD data LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP name');
    }
}
