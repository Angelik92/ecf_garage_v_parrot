<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Form\BrandsType;
use App\Repository\BrandsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/dashboard/marque')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class BrandsCrudController extends AbstractController
{
    #[Route('/', name: 'brands_index', methods: ['GET'])]
    public function index(BrandsRepository $brandsRepository , PaginatorInterface $paginator, Request $request): Response
    {
        // Paginate and display a list of brands
        $brands = $paginator->paginate(

            $brandsRepository->findAll(),

            $request->query->getInt('page', 1),
            9
        );
        return $this->render('admin/brands/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/nouveau', name: 'brands_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new brand and handle the form submission
        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new brand and show a success message
            $entityManager->persist($brand);
            $entityManager->flush();

            $this->addFlash('success', 'La marque a été ajoutée ! ');
            return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'La marque n\'a pas été ajoutée');
        }

        // Render the new brand form
        return $this->render('admin/brands/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'brands_show', methods: ['GET'])]
    public function show(Brands $brand): Response
    {
        // Display the details of a brand
        return $this->render('admin/brands/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/modifier', name: 'brands_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        // Edit an existing brand and handle the form submission
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes and show a success message
            $entityManager->flush();

            $this->addFlash('success', 'La marque a été modifiée ! ');
            return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'La marque n\'a pas été modifiée');
        }

        // Render the brand edit form
        return $this->render('admin/brands/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'brands_delete', methods: ['POST'])]
    public function delete(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            // Delete the brand
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        // Show a success message and redirect to the brand list
        $this->addFlash('success', 'La marque a été supprimée ! ');
        return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
    }
}
