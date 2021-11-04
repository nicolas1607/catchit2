<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use App\Form\UserInfoType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;
    private CommentRepository $commentRepo;

    public function __construct(EntityManagerInterface $em, CommentRepository $commentRepo)
    {
        $this->em = $em;
        $this->commentRepo = $commentRepo;
    }

    /**
     * @Route("/user/{id}", name="delete_user")
     */
    public function delete(Request $request, User $id): Response
    {
        $this->em->remove($id);
        $this->em->flush();

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(): Response
    {
        $user = $this->getUser();
        $comments = $this->commentRepo->findCommentByUser($user);
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/profile/info/modify", name="modify_info")
     */
    public function modifyInfo(Request $request): Response
    {
        $updateUserForm = $this->createForm(UserInfoType::class, $this->getUser());

        $updateUserForm->handleRequest($request);

        if ($updateUserForm->isSubmitted() && $updateUserForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/modify_profile.html.twig', [
            'modify_profile_form' => $updateUserForm->createView()
        ]);
    }

    /**
     * @Route("/profile/address/add", name="add_address")
     */
    public function addAddress(Request $request): Response
    {
        $user = $this->getUser();
        $address = new Address();

        $addAddressForm = $this->createForm(AddressType::class, $address);
        $addAddressForm->handleRequest($request);

        if ($addAddressForm->isSubmitted() && $addAddressForm->isValid()) {
            $address = $addAddressForm->getData();
            $user->setAddress($address);

            $this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/add_address.html.twig', [
            'add_address_form' => $addAddressForm->createView()
        ]);
    }

    /**
     * @Route("/profile/address/modify/{id}", name="modify_address")
     */
    public function modifyAddress(Request $request, Address $id): Response
    {
        $updateAddressForm = $this->createForm(AddressType::class, $id);
        $updateAddressForm->handleRequest($request);

        if ($updateAddressForm->isSubmitted() && $updateAddressForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/modify_address.html.twig', [
            'modify_address_form' => $updateAddressForm->createView()
        ]);
    }

    /**
     * @Route("/profile/address/delete/{id}", name="delete_address")
     */
    public function deleteAddress(Address $id): Response
    {
        $user = $this->getUser();
        $user->setAddress(null);
        $this->em->remove($id);
        $this->em->flush();

        return $this->redirectToRoute('profile');
    }
}
