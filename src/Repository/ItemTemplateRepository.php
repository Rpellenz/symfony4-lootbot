<?php

namespace App\Repository;

use App\Entity\ItemTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemTemplate[]    findAll()
 * @method ItemTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemTemplateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemTemplate::class);
    }


    /**
     * @param $item_id
     * @return mixed
     */
    public function findByItemId($item_id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.entry = :item_id')
            ->setParameter('item_id', $item_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ItemTemplate[] Returns an array of ItemTemplate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemTemplate
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
