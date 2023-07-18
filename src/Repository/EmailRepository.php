<?php

namespace OHMedia\EmailBundle\Repository;

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

    public function save(Email $email, bool $flush = false): void
    {
        $this->getEntityManager()->persist($email);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Email $email, bool $flush = false): void
    {
        $this->getEntityManager()->remove($email);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteSentBefore(\DateTimeInterface $sentBefore)
    {
        return $this->createQueryBuilder('e')
            ->delete()
            ->where('e.created_at < :sent_before')
            ->setParameter('sent_before', $sentBefore)
            ->getQuery()
            ->execute();
    }
}
