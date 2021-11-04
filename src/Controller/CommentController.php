<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Comment;
use DateTimeImmutable;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/comment/add/{item_id}", name="add_comment")
     */
    public function add(Request $request, Item $item_id): Response
    {
        $comment = new Comment();
        $addCommentForm = $this->createForm(CommentType::class, $comment);
        $addCommentForm->handleRequest($request);

        if ($addCommentForm->isSubmitted() && $addCommentForm->isValid()) {
            $comment = $addCommentForm->getData();
            $comment->setItem($item_id)
                ->setUser($this->getUser())
                ->setIsValid(false)
                ->setCreatedAt(new DateTimeImmutable());
            $item_id->addComment($comment);

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a été pris en compte et sera traité');

            return $this->redirectToRoute('show_item', ['id' => $item_id->getId()]);
        }

        return $this->render('comment/add.html.twig', [
            'item' => $item_id,
            'add_comment_form' => $addCommentForm->createView()
        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="delete_comment")
     */
    public function delete(Comment $comment): Response
    {
        $this->em->remove($comment);
        $this->em->flush();

        return $this->redirectToRoute('profile');
        // return $this->redirectToRoute('show_item', ['id' => $comment->getItem()->getId()]);
    }

    /**
     * @Route("/comment/valid/{id}", name="valid_comment")
     */
    public function valid(Comment $comment): Response
    {
        $comment->setIsValid(true);
        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectToRoute('admin_comment');
        // return $this->redirectToRoute('show_item', ['id' => $comment->getItem()->getId()]);
    }
}
