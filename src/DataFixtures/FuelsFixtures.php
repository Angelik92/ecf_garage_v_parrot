<?php

namespace App\DataFixtures;

use App\Entity\Fuels;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FuelsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1 ; $i<=5 ; $i++ ) {
            // Create a Fuel object with a fuel label
            $fuel = new Fuels();
            $fuel->setLabel('fuel '.$i);

            // Persist the Fuel object
            $manager -> persist($fuel);

            // Add a reference for this Fuel object
            $this->addReference('fuel ' . $i, $fuel);
        }

        // Execute all persisted changes
        $manager->flush();
    }
}