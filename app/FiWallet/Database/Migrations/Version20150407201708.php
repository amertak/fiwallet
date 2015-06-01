<?php

namespace FiWallet\Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150407201708 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts CHANGE balance balance NUMERIC(8, 2) NOT NULL');
        $this->addSql('ALTER TABLE reccurent_transactions CHANGE amount amount NUMERIC(8, 2) NOT NULL');
        $this->addSql('ALTER TABLE transactions CHANGE amount amount NUMERIC(8, 2) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts CHANGE balance balance DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE reccurent_transactions CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE transactions CHANGE amount amount DOUBLE PRECISION NOT NULL');
    }
}
