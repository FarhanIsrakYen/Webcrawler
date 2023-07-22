<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718204205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_turnover (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, year VARCHAR(255) NOT NULL, non_current_assets BIGINT NOT NULL, currennt_assets BIGINT NOT NULL, equity_capital BIGINT NOT NULL, liabilities BIGINT NOT NULL, sales_revenue BIGINT NOT NULL, profit_before_taxes BIGINT NOT NULL, profit_before_taxes_margin DOUBLE PRECISION NOT NULL, net_profit BIGINT NOT NULL, net_profit_margin DOUBLE PRECISION NOT NULL, INDEX IDX_1EAFB341979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_turnover ADD CONSTRAINT FK_1EAFB341979B1AD6 FOREIGN KEY (company_id) REFERENCES company_details (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_turnover DROP FOREIGN KEY FK_1EAFB341979B1AD6');
        $this->addSql('DROP TABLE company_turnover');
    }
}
