<?php

namespace FiWallet\Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150317104659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recurrent_transactions_tags (recurrent_transaction_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5FE1340B88672433 (recurrent_transaction_id), INDEX IDX_5FE1340BBAD26311 (tag_id), PRIMARY KEY(recurrent_transaction_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recurrent_transactions_tags ADD CONSTRAINT FK_5FE1340B88672433 FOREIGN KEY (recurrent_transaction_id) REFERENCES reccurent_transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recurrent_transactions_tags ADD CONSTRAINT FK_5FE1340BBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE accounts CHANGE currency currency VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE reccurent_transactions ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reccurent_transactions ADD CONSTRAINT FK_F629694E12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_F629694E12469DE2 ON reccurent_transactions (category_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recurrent_transactions_tags');
        $this->addSql('ALTER TABLE accounts CHANGE currency currency VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE reccurent_transactions DROP FOREIGN KEY FK_F629694E12469DE2');
        $this->addSql('DROP INDEX IDX_F629694E12469DE2 ON reccurent_transactions');
        $this->addSql('ALTER TABLE reccurent_transactions DROP category_id');
    }
}
