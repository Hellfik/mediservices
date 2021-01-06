<?php

namespace App\Repository;

use App\Entity\Pret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pret|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pret|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pret[]    findAll()
 * @method Pret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PretRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pret::class);
    }

    public function findByEndDate(){
        return $this->createQueryBuilder('p')
            ->where('p.pretStatus = :status')
            ->setParameter('status', 'Prêt en cours')
            ->orderBy('p.dateFin', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Return le nombre de pret qui ont le status pret en cours
     */

    public function numberOfLoan(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.pretStatus = :status')
            ->setParameter('status', 'Prêt en cours')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Return les prets qui ont le status annulé ou fini
     */
    public function findByStatus(){
        return $this->createQueryBuilder('p')
            ->where('p.pretStatus = :annule OR p.pretStatus = :fini')
            ->setParameters([
                'annule' => 'Pret annulé',
                'fini' => 'Pret finalisé'
            ])
            ->orderBy('p.dateFin', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recupère le nombre total de pret réalisé depuis la mise en service du systeme de management des prets
     */
    public function totalNumberOfLoan(){
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPretByMatId($id){
        return $this->createQueryBuilder('p')
            ->where('p.materiel = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Recupere les prets en fonction de l'id de l'utilisateur connecté
     *
     * @param integer $id
     * @return void
     */
    public function getPretByUser($id){
        return $this->createQueryBuilder('p')
            ->where('p.commercial = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Pret[] Returns an array of Pret objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pret
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
