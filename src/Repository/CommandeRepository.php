<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function add(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // used in admin-commande-non-récupéré
   /**
    * @return Commande[] Returns an array of Commande objects
    */
   public function findByCommandesNonRecupere(): array
   {
       return $this->createQueryBuilder('c')
            ->andWhere('c.isPaid = 1')
            ->andWhere('c.isRetrieved = 0')
            ->getQuery()
            ->getResult()
       ;
   }

    // used in admin-commande-récupéré
   /**
    * @return Commande[] Returns an array of Commande objects
    */
   public function findByCommandesRecupere(): array
   {
       return $this->createQueryBuilder('c')
            ->andWhere('c.isPaid = 1')
            ->andWhere('c.isRetrieved = 1')
            ->getQuery()
            ->getResult()
       ;
   }
   // used in stripe-success-paiement
   public function findOneNCommandePaid(): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.isPaid = 1')
           ->orderBy('c.nCommandePaid', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult()
       ;
   }


//    /**
//     * @return Commande[] Returns an array of Commande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
