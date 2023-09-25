<?php

namespace App\DataFixtures;

use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create news instances of Services
        for ($i = 1 ; $i <= 3; $i++ ) {
            $service = new Services();
            $service->setTitle('Service' . $i);


            if($i <=2){
                //generate price between 150 and 300
                $price = rand(150, 300);
                $service->setPrice($price);
            }

            $service->setDescription('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
             The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look
              like readable English.');

            // Persist the Service object to be saved in the database
            $manager->persist($service);

            // Add a reference to this object for future use if needed
            $this -> addReference('Service ' . $i, $service);

        }

        // Finalize the saving of all Service objects to the database
        $manager -> flush();

    }
}