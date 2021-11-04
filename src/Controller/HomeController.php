<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private AlbumRepository $albumRepo;
    private ItemRepository $itemRepo;

    public function __construct(AlbumRepository $albumRepo, ItemRepository $itemRepo)
    {
        $this->albumRepo = $albumRepo;
        $this->itemRepo = $itemRepo;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $lastAlbums = $this->albumRepo->findLastAlbums();
        $mostAlbums = $this->albumRepo->findMostAlbums();
        $mostItems = $this->itemRepo->findMostItems();

        return $this->render('home/index.html.twig', [
            'lastAlbums' => $lastAlbums,
            'mostAlbums' => $mostAlbums,
            'mostItems' => $mostItems
        ]);
    }
}
