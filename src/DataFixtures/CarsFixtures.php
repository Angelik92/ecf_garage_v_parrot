<?php

namespace App\DataFixtures;

use App\Entity\Cars;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $car = new Cars();

            $car->setModel('Nom du modèle'.$i);

            $brandIndex = rand(1, 5);
            $car->setBrand($this->getReference('brand ' . $brandIndex));

            $power = rand(50, 200);
            $car->setPower($power);

            $fuelIndex =rand(1,5);
            $car ->setFuel($this->getReference('fuel '. $fuelIndex));

            $gearboxIndex = rand(1,5);
            $car->setGearbox($this->getReference('gearbox' . $gearboxIndex));

            $manager ->persist($car);
            $this->addReference('car ' . $i, $car );
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            BrandsFixtures::class,
            GearboxesFixtures::class,
            FuelsFixtures::class
        );
    }
}