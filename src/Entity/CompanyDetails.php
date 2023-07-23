<?php

namespace App\Entity;

use App\Repository\CompanyDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyDetailsRepository::class)]
class CompanyDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $registrationCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vat = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: CompanyTurnover::class)]
    private Collection $companyTurnovers;

    public function __construct()
    {
        $this->companyTurnovers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRegistrationCode(): ?string
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(string $registrationCode): static
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(?string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, CompanyTurnover>
     */
    public function getCompanyTurnovers(): Collection
    {
        return $this->companyTurnovers;
    }

    public function addCompanyTurnover(CompanyTurnover $companyTurnover): static
    {
        if (!$this->companyTurnovers->contains($companyTurnover)) {
            $this->companyTurnovers->add($companyTurnover);
            $companyTurnover->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyTurnover(CompanyTurnover $companyTurnover): static
    {
        if ($this->companyTurnovers->removeElement($companyTurnover)) {
            // set the owning side to null (unless already changed)
            if ($companyTurnover->getCompany() === $this) {
                $companyTurnover->setCompany(null);
            }
        }

        return $this;
    }
}
