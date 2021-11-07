<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rating;
use App\Form\RatingFormType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    private EntityManagerInterface $em;
    private ItemRepository $itemRepo;

    public function __construct(EntityManagerInterface $em, ItemRepository $itemRepo)
    {
        $this->em = $em;
        $this->itemRepo = $itemRepo;
    }

    /**
     * @Route("/rating/add/{id}", name="add_rating")
     */
    public function add(Request $request, Item $item): Response
    {
        $user = $this->getUser();
        $rating = new Rating();
        $addRatingForm = $this->createForm(RatingFormType::class, $rating);
        $addRatingForm->handleRequest($request);

        $otherItems = $this->itemRepo->findBy(['name' => $item->getName()]);

        if ($addRatingForm->isSubmitted() && $addRatingForm->isValid()) {
            $rating = $addRatingForm->getData();
            $rating->setItem($item)
                ->setUser($user);
            $user->addRating($rating);
            // On met à jour la note globale des autres items du même nom 
            foreach ($otherItems as $otherItem) {
                $otherItem->addRating($rating);
                $this->em->persist($otherItem);
            }

            $this->em->persist($rating);
            $this->em->persist($user);
            $this->em->persist($item);
            $this->em->flush();

            return $this->redirectToRoute('show_item', ['id' => $item->getId()]);
        }

        return $this->render('rating/add.html.twig', [
            'item' => $item,
            'add_rating_form' => $addRatingForm->createView()
        ]);
    }

    /**
     * @Route("/rating/edit/{rating_id}", name="edit_rating")
     */
    public function edit(Request $request, Rating $rating_id): Response
    {
        $updateRatingForm = $this->createForm(RatingFormType::class, $rating_id);
        $updateRatingForm->handleRequest($request);

        if ($updateRatingForm->isSubmitted() && $updateRatingForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('show_item', ['id' => $rating_id->getItem()->getId()]);
        }

        return $this->render('rating/edit.html.twig', [
            'edit_rating_form' => $updateRatingForm->createView(),
            'item' => $rating_id->getItem()
        ]);
    }
}
