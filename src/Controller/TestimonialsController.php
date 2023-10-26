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
        $testimonials = $paginator->paginate(
            $testimonialsRepository->findBy(['approved' => 1],['date_of_service' => 'DESC']),
            $request->query->getInt('page', 1),
            4
        );

        $testimonial = new Testimonials();
        $testimonial->setCreateByPhone(false);
        $form = $this->createForm(ClientTestimonialsType::class, $testimonial);
        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the testimonial to the database
            $entityManager->persist($testimonial);
            $entityManager->flush();

            $this->addFlash('success', 'L\'avis a été ajouté ! ');
            return $this->redirectToRoute('testimonials', [], Response::HTTP_SEE_OTHER);
        } else if (($form->isSubmitted() && !$form->isValid())) {
            $this->addFlash('danger', 'L\'avis n\'a pas été créé ! ');
        }

        return $this->render('pages/testimonials/testimonials.html.twig', [
            'testimonials' => $testimonials,
            'form' => $form
        ]);
    }
}
