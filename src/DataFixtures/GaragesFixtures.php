<?php

namespace App\DataFixtures;

use App\Entity\Garages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GaragesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $garage = new Garages();
        $garage ->setName('Garage V PARROT');
        $garage -> setAddress('1 place de la liberté');
        $garage -> setZipCode(31000);
        $garage -> setCity('Toulouse');
        $garage -> setPhone('05.04.03.02.01');

        $manager->persist($garage);
        $manager->flush();
    }

}