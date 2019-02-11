<?php

namespace AppBundle\Repository;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Account p ORDER BY p.name ASC'
            )
            ->getResult();
    }
}
