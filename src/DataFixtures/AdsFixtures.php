<?php

namespace App\DataFixtures;

use App\Entity\Ads;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<=20; $i++){
            $ads = new Ads();

            // Set basic attributes
            $ads ->setRegistrationNb('ab' . $i . 'cd');
            $ads -> setTitle('titre annonce '. $i);
            $ads ->setCreateAt(new \DateTime());
            $ads-> setBuilt(Rand(1970, 2023));
            $ads -> setPrice(rand(5000, 40000));
            $ads ->setDescription('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.');
            $ads ->setKilometers(rand(2000, 100000));


            //Set attributes with randomly
            $userIndex = rand(1, 10);
            $ads -> setAuthor($this->getReference('user ' . $userIndex));

            $carIndex = rand(1, 10);
            $ads -> setCar($this->getReference('car ' . $carIndex));

            // Persist ads
            $manager->persist($ads);
        }

        // Execute all persisted changes
        $manager->flush();
    }

        // Define dependencies on other fixtures
        public function getDependencies()
        {
            return array(
                CarsFixtures::class,
                UserFixtures::class
            );
        }
}