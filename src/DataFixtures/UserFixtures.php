<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    //Constructor to inject UserPasswordHasherInterface
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        // Create and persist an admin user
        $admin = new User();
        $admin ->setEmail('parrot@example.com');
        $admin -> setLastname('PARROT' );
        $admin -> setFirstname('Vincent');
        $admin -> setCreateAt(new \DateTimeImmutable());

        //Hash and set the password
        $password = $this->hasher->hashPassword($admin, 'Pass_1234');
        $admin -> setPassword($password);
        $admin -> setRoles(['ROLE_ADMIN']);

        //Persist the admin user
        $manager->persist($admin);

        // Create and persist regular user objects
        for($i=1; $i<=10; $i++){
            $user = new User();
            $user ->setEmail('user' . $i . '@example.com');
            $user -> setLastname('Nom' . $i);
            $user -> setFirstname('Prénom' . $i);
            $user -> setCreateAt(new \DateTimeImmutable());

            // Hash and set the password
            $password = $this->hasher->hashPassword($user, 'Pass_1234');
            $user -> setPassword($password);
            $user -> setRoles(['ROLE_USER']);

            // Persist the regular user
            $manager->persist($user);

            // Add a reference for the regular user
            $this->addReference('user ' . $i, $user);

        }
        // Finalize the saving of all user objects to the database
        $manager->flush();
    }
}
