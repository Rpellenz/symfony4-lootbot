<?php

namespace App\Repository;

use App\Entity\DiscordMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiscordMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscordMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscordMember[]    findAll()
 * @method DiscordMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscordMemberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiscordMember::class);
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @return DiscordMember[] Returns an array of DiscordMember objects
     */
    public function findByDiscordId($guild_id, $member_id)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.guild_id = :guild')
            ->andWhere('d.discord_id = :member')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $guild_id
     * @return DiscordMember[] Returns an array of DiscordMember objects
     */
    public function findByGuild($guild_id)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.guild_id = :guild')
            ->setParameter('guild', $guild_id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return DiscordMember[] Returns an array of DiscordMember objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiscordMember
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
