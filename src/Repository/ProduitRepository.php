<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // used in navbar
    /**
     * Recherches les produits en fonction de mot entré par l'utilisateur
     * @return void 
     */
    public function search($mots){
        $query = $this->createQueryBuilder('p');
        $query->where('p.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(p.titre, p.marque) AGAINST(:mots boolean)>0')
                    ->setParameter('mots', $mots);
        }

        return $query->getQuery()->getResult();
    }

    // used in admin-produit-commandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByProduitsCommandables(): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.commandable = 1')
            ->getQuery()
            ->getResult()
       ;
   }
    // used in admin-produit-nonCommandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByProduitsNonCommandables(): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.commandable = 0')
            ->getQuery()
            ->getResult()
       ;
   }

   // used in admin-service-produits-commandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByServProdCommandables($serviceId): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.service = :id')
            ->andWhere('p.commandable = 1')
            ->setParameter('id', $serviceId)
            ->getQuery()
            ->getResult()
       ;
   }
    // used in admin-service-produits-nonCommandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByServProdNonCommandables($serviceId): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.service = :id')
            ->andWhere('p.commandable = 0')
            ->setParameter('id', $serviceId)
            ->getQuery()
            ->getResult()
       ;
   }

   // used in admin-categorie-produits-commandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByCatProdCommandables($categorieId): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :id')
            ->andWhere('p.commandable = 1')
            ->setParameter('id', $categorieId)
            ->getQuery()
            ->getResult()
       ;
   }
    // used in admin-categorie-produits-nonCommandables
   /**
    * @return Produit[] Returns an array of Commande objects
    */
   public function findByCatProdNonCommandables($categorieId): array
   {
       return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :id')
            ->andWhere('p.commandable = 0')
            ->setParameter('id', $categorieId)
            ->getQuery()
            ->getResult()
       ;
   }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
