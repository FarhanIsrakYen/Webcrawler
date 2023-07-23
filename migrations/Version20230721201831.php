<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721201831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_turnover CHANGE non_current_assets non_current_assets BIGINT DEFAULT NULL, CHANGE currennt_assets currennt_assets BIGINT DEFAULT NULL, CHANGE equity_capital equity_capital BIGINT DEFAULT NULL, CHANGE liabilities liabilities BIGINT DEFAULT NULL, CHANGE sales_revenue sales_revenue BIGINT DEFAULT NULL, CHANGE profit_before_taxes profit_before_taxes BIGINT DEFAULT NULL, CHANGE profit_before_taxes_margin profit_before_taxes_margin DOUBLE PRECISION DEFAULT NULL, CHANGE net_profit net_profit BIGINT DEFAULT NULL, CHANGE net_profit_margin net_profit_margin DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_turnover CHANGE non_current_assets non_current_assets BIGINT NOT NULL, CHANGE currennt_assets currennt_assets BIGINT NOT NULL, CHANGE equity_capital equity_capital BIGINT NOT NULL, CHANGE liabilities liabilities BIGINT NOT NULL, CHANGE sales_revenue sales_revenue BIGINT NOT NULL, CHANGE profit_before_taxes profit_before_taxes BIGINT NOT NULL, CHANGE profit_before_taxes_margin profit_before_taxes_margin DOUBLE PRECISION NOT NULL, CHANGE net_profit net_profit BIGINT NOT NULL, CHANGE net_profit_margin net_profit_margin DOUBLE PRECISION NOT NULL');
    }
}
