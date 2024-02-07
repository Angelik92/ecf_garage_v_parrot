<?php

namespace App\Controller;

use App\Entity\Testimonials;
use App\Form\ClientTestimonialsType;
use App\Repository\TestimonialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestimonialsController extends AbstractController
{
    #[Route('/avis', name: 'testimonials')]
    public function index(TestimonialsRepository $testimonialsRepository, PaginatorInterface $paginator, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieve and paginate approved testimonials
        $testimonials = $paginator->paginate(
            $testimonialsRepository->findBy(['approved' => 1],['date_of_service' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        // Create a new testimonial instance and set some initial values
        $testimonial = new Testimonials();
        $testimonial->setCreateByPhone(false);
        $form = $this->createForm(ClientTestimonialsType::class, $testimonial);
        $form ->handleRequest($request);

        // Create a form for submitting client testimonials
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the testimonial to the database
            $entityManager->persist($testimonial);
            $entityManager->flush();

            // Flash message for successful testimonial submission
            $this->addFlash('success', 'L\'avis a été ajouté ! ');
            return $this->redirectToRoute('testimonials', [], Response::HTTP_SEE_OTHER);
            // Flash message for unsuccessful testimonial creation
        } else if (($form->isSubmitted() && !$form->isValid())) {
            $this->addFlash('danger', 'L\'avis n\'a pas été créé ! ');
        }

        // Render the testimonials page with testimonials and the testimonial submission form
        return $this->render('pages/testimonials/testimonials.html.twig', [
            'testimonials' => $testimonials,
            'form' => $form
        ]);
    }
}
