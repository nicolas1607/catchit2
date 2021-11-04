<?php

namespace App\Controller;

use App\Entity\Album;
use DateTimeImmutable;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    private EntityManagerInterface $em;
    private AlbumRepository $albumRepo;

    public function __construct(EntityManagerInterface $em, AlbumRepository $albumRepo)
    {
        $this->em = $em;
        $this->albumRepo = $albumRepo;
    }

    /**
     * @Route("/browse", name="browse")
     */
    public function browse(): Response
    {
        $albums = $this->albumRepo->findCreateByAdminOrderByDate();
        return $this->render('album/browse.html.twig', [
            'albums' => $albums
        ]);
    }

    /**
     * @Route("/album", name="album")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('album/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/album/add", name="add_album")
     */
    public function add(Request $request): Response
    {
        $album = new Album();
        $addAlbumForm = $this->createForm(AlbumType::class, $album, array());
        $addAlbumForm->handleRequest($request);

        if ($addAlbumForm->isSubmitted() && $addAlbumForm->isValid()) {
            $album = $addAlbumForm->getData();
            $album->setCreatedAt(new DateTimeImmutable())
                ->setAdded(0);

            $this->em->persist($album);
            $this->em->flush();

            return $this->redirectToRoute('admin_collection');
        }

        return $this->render('album/add.html.twig', [
            'add_album_form' => $addAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/add/{id}", name="add_album_exist")
     */
    public function addExist(Album $id): Response
    {
        $user = $this->getUser();
        $albumOrigin = $this->albumRepo->findUserAlbumByName($id->getName());

        if ($albumOrigin) {
            $this->addFlash('danger', 'Vous posséder déjà cette collection');
            return $this->redirectToRoute('browse');
        } else {
            $album = new Album;
            $album->setName($id->getName())
                ->setDescription($id->getDescription())
                ->setUser($user);
            $album->setCreatedAt(new DateTimeImmutable());
            $user->addAlbum($album);

            $origin = $this->albumRepo->findByNameCreateByAdmin($album->getName());
            $origin->setAdded($origin->getAdded() + 1);

            $this->em->persist($user);
            $this->em->persist($album);
            $this->em->flush();

            $this->addFlash('success', $id->getName() . ' ajouté avec succès à vos collections !');
            return $this->redirectToRoute('browse');
        }
    }

    /**
     * @Route("/album/show/{id}", name="show_album")
     */
    public function show(Album $album): Response
    {
        $origin = $this->albumRepo->findByNameCreateByAdmin($album->getName());
        return $this->render('album/show.html.twig', [
            'album' => $album,
            'origin' => $origin
        ]);
    }

    /**
     * @Route("/album/edit/{id}", name="edit_album")
     */
    public function edit(Request $request, Album $id): Response
    {
        $updateAlbumForm = $this->createForm(AlbumType::class, $id);
        $updateAlbumForm->handleRequest($request);

        if ($updateAlbumForm->isSubmitted() && $updateAlbumForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('show_album', array('id' => $id->getId()));
        }

        return $this->render('album/edit.html.twig', [
            'edit_album_form' => $updateAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/{id}", name="delete_album")
     */
    public function delete(Album $id): Response
    {
        if (!$id->getUser()) {
            $albums = $this->albumRepo->findBy(['name' => $id->getName()]);
            foreach ($albums as $album) {
                $this->em->remove($album);
            }
        }
        $this->em->remove($id);
        $this->em->flush();

        if ($id->getUser()) {
            return $this->redirectToRoute('album');
        } else {
            return $this->redirectToRoute('admin_collection');
        }
    }
}
