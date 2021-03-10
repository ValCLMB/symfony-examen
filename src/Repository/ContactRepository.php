<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }


    //Récupère tous les objets Contact dont le champ email est 'test@test.com"
    public function show(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.email = :val')
            ->setParameter('val', 'test@test.com')
            ->orderBy('c.email', "ASC")
            ->getQuery()
            ->getResult();
    }
}
