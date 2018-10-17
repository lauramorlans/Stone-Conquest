<?php

namespace App\Repository;

use App\Entity\Jetons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findByArrayId() {
        return $this->createQueryBuilder('jj')
            ->from($this->getClassName(), 'j', 'jj.id')
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function findByTypeRang()
    {
        $jetons = $this->createQueryBuilder('j')
            ->orderBy('j.nom', 'ASC')
            ->orderBy('j.rang', 'DESC')
            ->getQuery()
            ->getResult();

        $tJetons= [];
        $tJetons['Baies']= [];
        $tJetons['Poisson']= [];
        $tJetons['Outils']= [];
        $tJetons['Armes']= [];
        $tJetons['Viande']= [];
        $tJetons['Feu']= [];
        $tJetons['Bonus 3']= [];
        $tJetons['Bonus 4']= [];
        $tJetons['Bonus 5']= [];

        foreach($jetons as $jeton){
            $tJetons[$jeton->getNom()][] = $jeton->getId();
        }

        shuffle($tJetons['Bonus 3']);
        shuffle($tJetons['Bonus 4']);
        shuffle($tJetons['Bonus 5']);

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
