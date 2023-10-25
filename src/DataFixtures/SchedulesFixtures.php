<?php

namespace App\DataFixtures;

use App\Entity\Schedules;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchedulesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Loop through days of the week (1 to 7)
        for($i=1; $i<=7 ; $i++){
            // Create a new Schedules object
            $schedule = new Schedules();


            $schedule->setDay('jour '.$i);

            // Set morning schedule based on the day
            if ($i <= 6){
                $schedule->setMorningSchedule('9h - 12h30');
            }else {
                $schedule->setMorningSchedule('FERME');
            }

            // Set afternoon schedule based on the day
            if ($i <= 5){
                $schedule->setAfternoonSchedule('14h - 19h');
            } else {
                $schedule->setAfternoonSchedule('FERME');
            }

            // Persist the schedule object to the database
            $manager->persist($schedule);

            // Flush changes to the database
            $manager->flush();
        }
    }
}