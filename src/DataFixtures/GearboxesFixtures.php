<?php

namespace App\DataFixtures;

use App\Entity\Gearboxes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GearboxesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create and persist Gearboxes objects
        for ($i= 1; $i <=5 ; $i++ ) {
            $gearbox = new Gearboxes();
            $gearbox->setLabel('gearbox ' . $i);

            // Persist the Gearboxes object
            $manager ->persist($gearbox);

            // Add a reference for this Gearboxes object
            $this ->addReference('gearbox'.$i, $gearbox);
        }
        // Execute all persisted changes
        $manager -> flush();
    }
}