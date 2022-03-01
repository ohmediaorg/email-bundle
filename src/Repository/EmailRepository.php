<?php

namespace OHMedia\EmailBundle\Repository;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use OHMedia\EmailBundle\Entity\Email;

/**
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }
    
    public function getUnsent()
    {
        return $this->createQueryBuilder('e')
            ->where('e.sending = 0 OR e.sending IS NULL')
            ->andWhere('e.sent_at IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function deleteSentBefore(DateTimeInterface $sent_before)
    {
        return $this->createQueryBuilder('e')
            ->delete()
            ->where('e.sent_at IS NOT NULL')
            ->andWhere('e.sent_at < :sent_before')
            ->setParameter('sent_before', $sent_before)
            ->getQuery()
            ->execute();
    }
}
