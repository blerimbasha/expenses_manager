<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransactionRepository extends EntityRepository
{
    public function searchAction()
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
    }
    public function groupTransactionAction()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createDate','DESC')
            ->groupBy('t.createDate')
            ->getQuery()
            ->getResult();
    }
}
