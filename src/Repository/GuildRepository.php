<?php

namespace App\Repository;

use App\Entity\Guild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Guild|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guild|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guild[]    findAll()
 * @method Guild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuildRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guild::class);
    }


    public function findByGuildId($guild_id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.guild_id = :guild_id')
            ->setParameter('guild_id', $guild_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Guild
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
