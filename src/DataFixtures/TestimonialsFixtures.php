<?php

namespace App\DataFixtures;

use App\Entity\Testimonials;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TestimonialsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Create and persist Testimonials objects
        for ($i = 1; $i <= 20; $i++) {

            $testimonial = new Testimonials();

            // Set attributes basic
            $testimonial->setDateOfService(new \DateTimeImmutable());
            $testimonial->setClient('client ' . $i);
            $testimonial->setContent('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.');

            // Set a reference to a random service (Service 1, Service 2, or Service 3)
            $serviceIndex = rand(1, 3);
            $testimonial->setService($this->getReference('Service ' . $serviceIndex));

            // Generate a random rating between 0 and 5
            $ratingRandom = rand(0,5);
            $testimonial-> setRating($ratingRandom);

            // Set a reference to a random user (User 1 to User 10)
            $userIndex = rand(1, 10);
            $testimonial->setModerator($this->getReference('user ' . $userIndex));

            // Set approval status based on $i value
            if($i <=8){
                $testimonial->setApproved(true);
            } elseif ($i <= 14) {
                $testimonial-> setApproved(false);
            }
            // Persist the testimonial object
            $manager ->persist($testimonial);
        }
        // Execute all persisted changes
        $manager->flush();
        }
    // Define dependencies on other fixtures
    public function getDependencies()
    {
        return array(
            ServicesFixtures::class,
            UserFixtures::class
        );
    }
}