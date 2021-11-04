<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // /**
    //  * @return Comment[] Retournes la liste des commentaires pour un item
    //  */
    public function findCommentByItem(Item $item): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:comment r
                INNER JOIN App:item i
                WITH r.item = " . $item->getId() . "
                WHERE r.isValid = true
                ORDER BY r.createdAt DESC"
            )
            ->getResult();
    }

    // /**
    //  * @return Comment[] Retournes la liste des commentaires pour un utilisateur
    //  */
    public function findCommentByUser(User $user): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:comment r
                WHERE r.user = " . $user->getId() . "
                ORDER BY r.createdAt DESC"
            )
            ->getResult();
    }

    // /**
    //  * @return Comment[] Retournes la liste des commentaires non validés
    //  */
    public function findCommentNoValid(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:comment r
                WHERE r.isValid = false"
            )
            ->getResult();
    }
}
