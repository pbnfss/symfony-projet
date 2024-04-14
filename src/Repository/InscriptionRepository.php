<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    public function rechInscriptionEmploye($nom, $prenom): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.employe','e')
            ->andWhere('e.nom = :val1')
            ->andWhere('e. prenom = :val2')
            ->setParameter('val1', $nom)
            ->setParameter('val2', $prenom)
            ->getQuery()
            ->getResult()
            ;
    }

}


