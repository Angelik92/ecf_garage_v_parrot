<?php

namespace App\DataFixtures;

use App\Entity\Brands;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create and persist Brands objects
        for ($i = 1 ; $i <= 5 ; $i++) {
            $brand = new Brands();
            $brand -> setName('nom de marque ' . $i);

            // Persist the Brands object
            $manager -> persist($brand);

            // Add a reference for this Brands object
            $this -> addReference('brand ' . $i, $brand);
        }

        // Execute all persisted changes
        $manager->flush();
    }
}