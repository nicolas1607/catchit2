<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    // /**
    //  * @return Album[] Retourne les albums crées par l'admin
    //  */
    public function findCreateByAdmin(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App:album a
                WHERE a.user is NULL
                ORDER BY a.id ASC"
            )
            ->getResult();
    }

    // /**
    //  * @return Album[] Retourne un album crées par l'admin selon le nom
    //  */
    public function findByNameCreateByAdmin(String $album): Album
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App:album a
                WHERE a.user IS NULL
                AND a.name = '" . $album . "'"
            )
            ->getResult()[0];
    }

    // /**
    //  * @return Album[] Retourne les albums crées par l'admin ordoné par date
    //  */
    public function findCreateByAdminOrderByDate(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT alb FROM App:album alb
                WHERE alb.user is NULL
                ORDER BY alb.createdAt DESC"
            )
            ->getResult();
    }

    // /**
    //  * @return Album[] Retourne les 6 derniers albums
    //  */
    public function findLastAlbums(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT alb FROM App:album alb
                WHERE alb.user is NULL
                ORDER BY alb.createdAt DESC"
            )
            ->setMaxResults(6)
            ->getResult();
    }

    // /**
    //  * @return Album[] Retournes les 6 albums les plus ajoutés
    //  */
    public function findMostAlbums(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App:album a
                WHERE a.user IS NULL
                ORDER BY a.added DESC"
            )
            ->setMaxResults(6)
            ->getResult();
    }

    // /**
    //  * @return Album[] Retourne l'album utilisateur selon un nom d'album
    //  */
    public function findUserAlbumByName(String $name): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App:album a
                WHERE a.user IS NOT NULL
                AND a.name = '" . $name . "'"
            )
            ->getResult();
    }

    // /**
    //  * @return Album[] Retourne l'album utilisateur selon un item
    //  */
    public function findUserAlbumByItem(Int $id): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM App:album a
                WHERE a.user IS NOT NULL
                AND a.name IN (
                    SELECT alb.name FROM App:album alb
                    INNER JOIN App:item i
                    WITH i.album = alb.id
                    WHERE i.id = " . $id . ")"
            )
            ->getResult();
    }
}
