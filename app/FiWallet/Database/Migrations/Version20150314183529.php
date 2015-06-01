<?php

namespace FiWallet\Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150314183529 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, INDEX IDX_CAC89EACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filters (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, data LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_7877678DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_time DATETIME NOT NULL, data LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reccurent_transactions (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, is_active TINYINT(1) NOT NULL, occurence_interval INT NOT NULL, first_occurence DATE NOT NULL, type VARCHAR(255) NOT NULL, day_of_week INT DEFAULT NULL, day_of_month INT DEFAULT NULL, INDEX IDX_F629694E9B6B5FBA (account_id), INDEX IDX_F629694EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6FBC9426A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, recurrent_transaction_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, date_of_transaction DATE NOT NULL, created DATETIME NOT NULL, is_confirmed TINYINT(1) NOT NULL, INDEX IDX_EAA81A4C9B6B5FBA (account_id), INDEX IDX_EAA81A4C12469DE2 (category_id), INDEX IDX_EAA81A4CA76ED395 (user_id), INDEX IDX_EAA81A4C88672433 (recurrent_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions_tags (transaction_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E3C4F1302FC0CB0F (transaction_id), INDEX IDX_E3C4F130BAD26311 (tag_id), PRIMARY KEY(transaction_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reccurent_transactions ADD CONSTRAINT FK_F629694E9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE reccurent_transactions ADD CONSTRAINT FK_F629694EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC9426A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C88672433 FOREIGN KEY (recurrent_transaction_id) REFERENCES reccurent_transactions (id)');
        $this->addSql('ALTER TABLE transactions_tags ADD CONSTRAINT FK_E3C4F1302FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions_tags ADD CONSTRAINT FK_E3C4F130BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reccurent_transactions DROP FOREIGN KEY FK_F629694E9B6B5FBA');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9B6B5FBA');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C12469DE2');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C88672433');
        $this->addSql('ALTER TABLE transactions_tags DROP FOREIGN KEY FK_E3C4F130BAD26311');
        $this->addSql('ALTER TABLE transactions_tags DROP FOREIGN KEY FK_E3C4F1302FC0CB0F');
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACA76ED395');
        $this->addSql('ALTER TABLE filters DROP FOREIGN KEY FK_7877678DA76ED395');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE reccurent_transactions DROP FOREIGN KEY FK_F629694EA76ED395');
        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC9426A76ED395');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE filters');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE reccurent_transactions');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE transactions_tags');
        $this->addSql('DROP TABLE users');
    }
}
