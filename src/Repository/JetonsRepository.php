<?php

namespace App\Repository;

use App\Entity\Jetons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Jetons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jetons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jetons[]    findAll()
 * @method Jetons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JetonsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Jetons::class);
    }

    public function findByTypeRang()
    {
        $jetons = $this->createQueryBuilder('j')
            ->orderBy('j.type', 'ASC')
            ->orderBy('j.valeur', 'DESC')
            ->getQuery()
            ->getResult();

        $tJetons= [];
        $tJetons['type1']= [];
        $tJetons['type2']= [];
        $tJetons['type3']= [];
        $tJetons['type4']= [];
        $tJetons['type5']= [];
        $tJetons['type6']= [];

        foreach($jetons as $jeton){
            $tJetons[$jeton->getType()][] = $jeton->getId();
        }
        return $tJetons;
    }

//    /**
//     * @return Jetons[] Returns an array of Jetons objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jetons
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
