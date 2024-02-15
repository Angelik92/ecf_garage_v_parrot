<?php

namespace App\Twig\Runtime;

use App\Repository\GaragesRepository;
use App\Repository\SchedulesRepository;
use Twig\Extension\RuntimeExtensionInterface;

class FooterRuntime implements RuntimeExtensionInterface
{
    private $garagesRepository;
    private $schedulesRepository;
    public function __construct(GaragesRepository $garagesRepository, SchedulesRepository $schedulesRepository)
    {
        $this->garagesRepository = $garagesRepository;
        $this->schedulesRepository = $schedulesRepository;
    }

    public function getDataFooter()
    {

        $schedules = $this->schedulesRepository->findAll();
        $garages = $this->garagesRepository->findAll();

        return [
            'schedules' => $schedules,
            'garages' => $garages,
        ];
    }
}
