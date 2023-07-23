<?php

namespace App\Repository;

use App\Entity\CompanyDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends ServiceEntityRepository<CompanyDetails>
 *
 * @method CompanyDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyDetails[]    findAll()
 * @method CompanyDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyDetailsRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyDetails::class);
        $this->manager = $registry->getManager('default');
    }

    public function createCompanyDetail(
        string $name,
        ?string $vat,
        string $registrationCode,
        string $address,
        string $phone
    ): CompanyDetails {
        $company = new CompanyDetails();
        $company->setName($name)
                ->setRegistrationCode($registrationCode)
                ->setVat($vat)
                ->setAddress($address)
                ->setPhone($phone);

        $this->manager->persist($company);
        $this->manager->flush();

        return $company;
    }
}
