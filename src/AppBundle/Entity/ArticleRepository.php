<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function countAll()
    {
        $em = $this->getEntityManager();

        // "SELECT count(*) FROM Articles"
        $query = $em->createQuery("SELECT count(a) FROM AppBundle\Entity\Article a");
        return $query->getSingleScalarResult();
    }

    public function findByOffsetAndLimitWithAuthor($offset = 0, $limit) {

        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT a, au FROM AppBundle\Entity\Article a JOIN a.author au ORDER BY a.createOn DESC");
        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

}
