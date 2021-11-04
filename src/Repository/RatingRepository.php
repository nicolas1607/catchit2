<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Rating;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    // /**
    //  * @return Rating Retourne un avis en fonction d'un item et d'un user
    //  */
    public function findByItemAndUser(Item $item, User $user): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:rating r
                WHERE r.item = " . $item->getId() . "
                AND r.user = " . $user->getId()
            )
            ->getResult();
    }
}
