<?php

namespace App\Controller;

use App\Repository\AdsRepository;
use App\Repository\ServicesRepository;
use App\Repository\TestimonialsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ServicesRepository $servicesRepository, AdsRepository $adsRepository, TestimonialsRepository $testimonialsRepository): Response
    {
        $services = $servicesRepository->findAll();

        $ads = $adsRepository->findBy([], ['id' => 'DESC'], 6);

        $testimonials = $testimonialsRepository->findBy(['approved' => 1], ['id' => 'DESC'], 6);


        return $this->render('pages/homepage.html.twig', [
            'services' => $services,
            'ads' => $ads,
            'testimonials' => $testimonials
        ]);
    }
}
