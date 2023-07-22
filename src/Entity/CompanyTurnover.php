<?php

namespace App\Entity;

use App\Repository\CompanyTurnoverRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyTurnoverRepository::class)]
class CompanyTurnover
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'companyTurnovers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CompanyDetails $company = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $nonCurrentAssets = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $currentAssets = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $equityCapital = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $liabilities = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $salesRevenue = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $profitBeforeTaxes = null;

    #[ORM\Column(nullable: true)]
    private ?float $profitBeforeTaxesMargin = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $netProfit = null;

    #[ORM\Column(nullable: true)]
    private ?float $netProfitMargin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?CompanyDetails
    {
        return $this->company;
    }

    public function setCompany(?CompanyDetails $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getNonCurrentAssets(): ?string
    {
        return $this->nonCurrentAssets;
    }

    public function setNonCurrentAssets(?string $nonCurrentAssets): static
    {
        $this->nonCurrentAssets = $nonCurrentAssets;

        return $this;
    }

    public function getCurrentAssets(): ?string
    {
        return $this->currentAssets;
    }

    public function setCurrentAssets(?string $currentAssets): static
    {
        $this->currentAssets = $currentAssets;

        return $this;
    }

    public function getEquityCapital(): ?string
    {
        return $this->equityCapital;
    }

    public function setEquityCapital(?string $equityCapital): static
    {
        $this->equityCapital = $equityCapital;

        return $this;
    }

    public function getLiabilities(): ?string
    {
        return $this->liabilities;
    }

    public function setLiabilities(?string $liabilities): static
    {
        $this->liabilities = $liabilities;

        return $this;
    }

    public function getSalesRevenue(): ?string
    {
        return $this->salesRevenue;
    }

    public function setSalesRevenue(?string $salesRevenue): static
    {
        $this->salesRevenue = $salesRevenue;

        return $this;
    }

    public function getProfitBeforeTaxes(): ?string
    {
        return $this->profitBeforeTaxes;
    }

    public function setProfitBeforeTaxes(?string $profitBeforeTaxes): static
    {
        $this->profitBeforeTaxes = $profitBeforeTaxes;

        return $this;
    }

    public function getProfitBeforeTaxesMargin(): ?float
    {
        return $this->profitBeforeTaxesMargin;
    }

    public function setProfitBeforeTaxesMargin(?float $profitBeforeTaxesMargin): static
    {
        $this->profitBeforeTaxesMargin = $profitBeforeTaxesMargin;

        return $this;
    }

    public function getNetProfit(): ?string
    {
        return $this->netProfit;
    }

    public function setNetProfit(?string $netProfit): static
    {
        $this->netProfit = $netProfit;

        return $this;
    }

    public function getNetProfitMargin(): ?float
    {
        return $this->netProfitMargin;
    }

    public function setNetProfitMargin(?float $netProfitMargin): static
    {
        $this->netProfitMargin = $netProfitMargin;

        return $this;
    }
}
