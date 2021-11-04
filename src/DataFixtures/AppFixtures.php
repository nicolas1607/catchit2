<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\Album;
use DateTimeImmutable;
use App\Entity\Address;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passHasher;

    public function __construct(UserPasswordHasherInterface $passHasher)
    {
        $this->passHasher = $passHasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = $this->createAdminUser();
        $manager->persist($admin);
        $manager->flush();

        $user = $this->createAdminCefim();
        $manager->persist($user);
        $manager->flush();

        $user = $this->createUser();
        $manager->persist($user);
        $manager->flush();

        // $faker = Factory::create();

        // User Entity
        // for ($i = 0; $i < 10; $i++) {
        //     $address = new Address;
        //     $address->setStreet($faker->streetAddress)
        //         ->setPostalCode($faker->postcode)
        //         ->setCity($faker->city)
        //         ->setCountry($faker->country);
        //     $user = new User;
        //     $user->setEmail($faker->email)
        //         ->setPassword($this->passHasher->hashPassword($user, $faker->password))
        //         ->setRoles([])
        //         ->setAddress($address);
        //     $manager->persist($user);
        // }

        // Item Entity
        // for ($i = 0; $i < 30; $i++) {
        //     $item = new Item;
        //     $item->setName($faker->word);
        //     $manager->persist($item);
        // }

        // Album Entity
        // for ($k = 0; $k < 10; $k++) {
        //     $album = new Album;
        //     $album->setName($faker->word)
        //         ->setDescription($faker->text)
        //         ->setCreatedAt(new DateTimeImmutable($faker->date));
        //     $manager->persist($album);
        // }
    }

    public function createAdminUser(): User
    {
        $faker = Factory::create();

        $admin = new User;
        $admin->setEmail('nicolas160796@gmail.com')
            ->setPassword('$2y$13$GYXDjaRD1PDQ8S2JhH0gWOI6UWsuhXVPFMPqEpUPXaeHyN60yQm6i')
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Nicolas')
            ->setLastname('Mormiche')
            ->setContact('0627712403');
        return $admin;
    }

    public function createAdminCefim(): User
    {
        $faker = Factory::create();

        $admin = new User;
        $admin->setEmail('mauger@cefim.eu')
            ->setPassword('$2y$13$jfYUpi5z/9hYwCX/XnHzH.9f0TMergRhMYBQXibGoJrR2xxv6PnPO')
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Mickaël')
            ->setLastname('Auger');
        return $admin;
    }

    public function createUser(): User
    {
        $faker = Factory::create();

        $admin = new User;
        $admin->setEmail('adrien.sandras@cube.com')
            ->setPassword('$2y$13$GYXDjaRD1PDQ8S2JhH0gWOI6UWsuhXVPFMPqEpUPXaeHyN60yQm6i')
            ->setRoles([""])
            ->setFirstname('Nicolas')
            ->setLastname('Mormiche');
        return $admin;
    }
}
