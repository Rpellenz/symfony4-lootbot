<?php

namespace App\Repository;

use App\Entity\LootList;
use DateInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LootList|null find($id, $lockMode = null, $lockVersion = null)
 * @method LootList|null findOneBy(array $criteria, array $orderBy = null)
 * @method LootList[]    findAll()
 * @method LootList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LootListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LootList::class);
    }

    /**
     * @param $guild_id
     * @param $days
     * @return LootList[] Returns an array of LootList objects
     * @throws \Exception
     */
    public function findByGuild($guild_id, $days=0)
    {
        $interval = (string)'P' . $days . 'D';
        $date = new \DateTime("now");
        $date->sub(new DateInterval($interval));

        return $this->createQueryBuilder('l')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.insert_ts > :date')
            ->setParameter('guild', $guild_id)
            ->setParameter('date', $date)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @param $days
     * @return LootList[] Returns an array of LootList objects
     * @throws \Exception
     */
    public function findByMember($guild_id, $member_id, $days=0)
    {
        $interval = (string)'P' . $days . 'D';
        $date = new \DateTime("now");
        $date->sub(new DateInterval($interval));

        return $this->createQueryBuilder('l')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.member_id = :member')
            ->andWhere('l.insert_ts > :date')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->setParameter('date', $date)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $guild_id
     * @return LootList[] Returns an array of LootList objects
     * @throws \Exception
     */
    public function getLootCountForGuild($guild_id)
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->andWhere('l.guild_id = :guild')
            ->setParameter('guild', $guild_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @return LootList[] Returns an array of LootList objects
     * @throws \Exception
     */
    public function getLootCountForMember($guild_id, $member_id)
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.member_id = :member')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @param $days
     * @return LootList[] Returns an array of LootList objects
     * @throws NonUniqueResultException
     */
    public function getLootCountForUserAndDays($guild_id, $member_id, $days)
    {
        $interval = (string)'P' . $days . 'D';
        $date = new \DateTime("now");
        $date->sub(new DateInterval($interval));

        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.member_id = :member')
            ->andWhere('l.insert_ts > :date')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $guild_id
     * @param $item_name
     * @return LootList[] Returns an array of LootList objects
     * @throws \Exception
     */
    public function findByItem($guild_id, $item_name)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.item_name LIKE :name')
            ->setParameter('guild', $guild_id)
            ->setParameter('name', '%' . $item_name . '%')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * @param $guild_id
     * @return mixed
     * @throws \Exception
     */
    public function deleteByGuild($guild_id)
    {
        return $this->createQueryBuilder('l')
            ->delete()
            ->andWhere('l.guild_id = :guild')
            ->setParameter('guild', $guild_id)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @return mixed
     */
    public function deleteByGuildAndMemberId($guild_id, $member_id)
    {
        return $this->createQueryBuilder('l')
            ->delete()
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.member_id = :member')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $guild_id
     * @param $member_id
     * @param $item_name
     * @return mixed
     */
    public function getItemByGuildAndMemberIdAndItemName($guild_id, $member_id, $item_name)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.guild_id = :guild')
            ->andWhere('l.member_id = :member')
            ->andWhere('l.item_name = :item')
            ->setParameter('guild', $guild_id)
            ->setParameter('member', $member_id)
            ->setParameter('item', $item_name)
            ->orderBy('l.insert_ts', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // DELETE FROM loot WHERE guild_id = ? AND user_id = ? AND item_name = ? LIMIT 1'

    // /**
    //  * @return LootList[] Returns an array of LootList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LootList
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
