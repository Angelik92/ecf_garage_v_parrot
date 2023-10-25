<?php

namespace App\Controller;

use App\Entity\Testimonials;
use App\Form\TestimonialsType;
use App\Form\TestimonialsValidationType;
use App\Repository\TestimonialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/avis')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class TestimonialsCrudController extends AbstractController
{
    #[Route('/', name: 'testimonials_index', methods: ['GET'])]
    public function index(TestimonialsRepository $testimonialsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Paginate testimonials
        $testimonials = $paginator->paginate(
            $testimonialsRepository->findBy([], ['approved' => 'ASC']),
            $request->query->getInt('page', 1),
            4

        );
        return $this->render('admin/testimonials/index.html.twig', [
            'testimonials' => $testimonials,
        ]);
    }

    #[Route('/nouveau', name: 'testimonials_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new testimonial
        $testimonial = new Testimonials();
        $form = $this->createForm(TestimonialsType::class, $testimonial);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the testimonial to the database
            $entityManager->persist($testimonial);
            $entityManager->flush();

            $this->addFlash('success', 'L\'avis a été ajouté ! ');
            return $this->redirectToRoute('testimonials_index', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('danger', 'L\'avis n\'a pas été créé ! ');
        }

        return $this->render('admin/testimonials/new.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'testimonials_show', methods: ['GET'])]
    public function show(Testimonials $testimonial): Response
    {
        // Display details of a testimonial
        return $this->render('admin/testimonials/show.html.twig', [
            'testimonial' => $testimonial,
        ]);
    }

    #[Route('/{id}/modifier', name: 'testimonials_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonials $testimonial, EntityManagerInterface $entityManager): Response
    {
        // Check if the testimonial was created by phone
        if ($testimonial->isCreateByPhone()) {
            $this->denyAccessUnlessGranted('TESTIMONIAL_EDIT', $testimonial);
            $form = $this->createForm(TestimonialsType::class, $testimonial);

        } else {
            $this->denyAccessUnlessGranted('TESTIMONIAL_VALIDATE', $testimonial);
            $form = $this->createForm(TestimonialsValidationType::class, $testimonial);

        }

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Update the approved status and persist the testimonial
            if ($testimonial->isCreateByPhone()) {
                $approved = $form->get('approved')->getData();
                $testimonial->setApproved($approved);
            }
            $entityManager->flush();

            $this->addFlash('success', 'L\'avis a été modifié ! ');
            return $this->redirectToRoute('testimonials_index', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('danger', 'L\'avis n\'a pas été modifié ! ');
        }

        return $this->render('admin/testimonials/edit.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'testimonials_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Testimonials $testimonial, EntityManagerInterface $entityManager): Response
    {
        // Delete a testimonial (admin only)
        if ($this->isCsrfTokenValid('delete'.$testimonial->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimonial);
            $entityManager->flush();
        }
        $this->addFlash('success', 'L\'avis a été supprimé ! ');
        return $this->redirectToRoute('testimonials_index', [], Response::HTTP_SEE_OTHER);
    }
}
