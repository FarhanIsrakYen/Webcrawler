<?php

namespace App\Repository;

use App\Entity\CompanyDetails;
use App\Entity\CompanyTurnover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends ServiceEntityRepository<CompanyTurnover>
 *
 * @method CompanyTurnover|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyTurnover|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyTurnover[]    findAll()
 * @method CompanyTurnover[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyTurnoverRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyTurnover::class);
        $this->manager = $registry->getManager('default');
    }

    public function createCompanyDetail(
        CompanyDetails $company,
        string $year,
        ?string $nonCurrentAssets,
        ?string $currenntAssets,
        ?string $equityCapital,
        ?string $liabilities,
        ?string $salesRevenue,
        ?string $profitBeforeTaxes,
        ?float $profitBeforeTaxesMargin,
        ?string $netProfit,
        ?float $netProfitMargin
    ): CompanyTurnover {
        $companyTurnover = new CompanyTurnover();
        $companyTurnover->setCompany($company)
                ->setYear($year)
                ->setNonCurrentAssets($nonCurrentAssets)
                ->setCurrentAssets($currenntAssets)
                ->setLiabilities($liabilities)
                ->setEquityCapital($equityCapital)
                ->setSalesRevenue($salesRevenue)
                ->setProfitBeforeTaxes($profitBeforeTaxes)
                ->setProfitBeforeTaxesMargin($profitBeforeTaxesMargin)
                ->setNetProfit($netProfit)
                ->setNetProfitMargin($netProfitMargin);

        $this->manager->persist($companyTurnover);
        $this->manager->flush();

        return $companyTurnover;
    }

    public function deleteMultipleData(int $id)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->delete()
           ->where('t.company = :company')
           ->setParameters([
               'company' => $id
           ]);

        return $qb->getQuery()->execute();
    }
}
