<?php

namespace App\Repository;

use App\Entity\Classe;
use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function findAllByEtudiant(Etudiant $etudiant){
        return $this->createQueryBuilder('u')
                    ->select('u.id, u.nom, u.prenom, u.age, u.anneestart, u.nompromo')
                    ->setParameter('nompromo', $etudiant)
                    ->getQuery()
                    ->getResult();

    }

    public function findAllByPromo(Classe $classe){
        return $this->createQueryBuilder('t')
                    ->select('t.id, t.nompromo, t.anneefin')
                    ->where('t.nompromo = :nompromo')
                    ->setParameter('nompromo', $classe)
                    ->getQuery()
                    ->getResult();
            
    }

    // /**
    //  * @return Etudiant[] Returns an array of Etudiant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etudiant
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
