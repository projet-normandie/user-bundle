<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class UserRepository extends EntityRepository
{

    /**
     * @param $q
     * @return mixed
     */
    public function autocomplete($q)
    {
        $query = $this->createQueryBuilder('u');

        $query
            ->where('u.username LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->andWhere('u.enabled = 1')
            ->orderBy('u.username', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * Maj badges
     */
    public function majBadge()
    {
        $sql = " INSERT INTO user_badge (idUser, idBadge)
        SELECT user.id,badge.id
        FROM user,badge
        WHERE type = '%s'
        AND value <= user.%s
        AND badge.id NOT IN (SELECT idBadge FROM user_badge WHERE idUser= user.id)";

        $this->_em->getConnection()->executeUpdate(sprintf($sql, 'Connexion', 'nbConnexion'));
        $this->_em->getConnection()->executeUpdate(sprintf($sql, 'Forum', 'nbForumMessage'));

        // Inscrition badge
        $sql = " INSERT INTO user_badge (idUser, idBadge)
        SELECT user.id,1
        FROM user
        WHERE id NOT IN (SELECT idUser FROM user_badge WHERE idBadge = 1)";

        $this->_em->getConnection()->executeUpdate($sql);
    }
}
