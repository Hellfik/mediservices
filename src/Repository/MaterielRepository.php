<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    /**
     * Return une query
     * @return Query
     */

    public function findAllQuery(){

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->getQuery()
                    ;
    }

    /**
     * Return le nombre de materiel dans la bdd
     */
    public function numberOfMateriel(){
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Return le nombre de materiaux qui ont le status disponible
     */

    public function numberOfMaterielAvailable(){
        return $this->createQueryBuilder('m')
            ->andWhere('m.status = :status')
            ->setParameter('status', 'Disponible')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Return le nombre de materiaux qui ont le status maintenance
     */

    public function numberOfMaterielRepair(){
        return $this->createQueryBuilder('m')
            ->andWhere('m.status = :status')
            ->setParameter('status', 'En maintenance')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBySearchQuery($search){
        return $this->createQueryBuilder('m')
            ->where('m.serialNumber = :search')
            ->setParameter('search', $search)
            ->getQuery();
    }

    public function findByCatQuery($filter){
        return $this->createQueryBuilder('m')
            ->where('m.category = :filter')
            ->setParameter('filter', $filter)
            ->getQuery();
    }
    // /**
    //  * @return Materiel[] Returns an array of Materiel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Materiel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
