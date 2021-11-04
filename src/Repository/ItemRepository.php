<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Retournes les items crées par l'admin
    //  */
    public function findCreateByAdmin(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT i FROM App:item i
                    INNER JOIN App:album a
                    WITH i.album = a.id
                    WHERE a.user IS NULL"
            )
            ->getResult();
    }

    // /**
    //  * @return Item[] Retourne les 6 items les plus ajoutés
    //  */
    public function findMostItems(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT i FROM App:item i
                INNER JOIN App:album a
                WITH i.album = a.id
                WHERE a.user IS NULL
                ORDER BY i.added DESC"
            )
            ->setMaxResults(6)
            ->getResult();
    }

    // /**
    //  * @return Item[] Retourne l'item un utilisateur selon un nom
    //  */
    public function findUserByIdAndItemByName(Int $id, String $name): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT i.name FROM App:user u
                INNER JOIN App:album a
                WITH u.id = a.user
                INNER JOIN App:item i
                WITH a.id = i.album
                WHERE u.id = " . $id . "
                AND i.name = '" . $name . "'"
            )
            ->getResult();
    }

    // /**
    //  * @return Int Retourne le nombre d'avis d'un item
    //  */
    public function findCountComment(Int $id): int
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT count(r.message) FROM App:comment r
                INNER JOIN App:item i
                WITH r.item = i.id
                WHERE r.isValid = true
                AND i.id = " . $id
            )
            ->getResult()[0][1];
    }

    // /**
    //  * @return Int Retourne le nombre d'item pour un album
    //  */
    public function findCountByOneAlbum(String $collection): int
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT count(i.name) FROM App:item i
                INNER JOIN App:album a
                WITH a.id = i.album
                WHERE a.name = '" . $collection . "'"
            )
            ->getResult()[0][1];
    }

    // /**
    //  * @return Int Retourne la valeur de sa note /5
    //  */
    public function findRating(Item $item): ?int
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT AVG(r.rate) FROM App:item i
                INNER JOIN App:rating r
                WITH r.item = i.id
                WHERE i.id = '" . $item->getId() . "'"
            )
            ->getResult()[0][1];
    }

    // /**
    //  * @return Int Retourne la valeur de sa note /5
    //  */
    public function findRatingScoreByUser(Item $item, User $user): ?array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r.rate FROM App:item i
                INNER JOIN App:rating r
                WITH r.item = i.id
                INNER JOIN App:user u
                WITH r.user = u.id
                WHERE i.id = '" . $item->getId() . "' 
                AND u.id = " . $user->getId()
            )
            ->getResult();
    }
}
