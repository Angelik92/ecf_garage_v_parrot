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
        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($brand);
            $entityManager->flush();

            $this->addFlash('success', 'La marque a été ajoutée ! ');
            return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/brands/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'brands_show', methods: ['GET'])]
    public function show(Brands $brand): Response
    {
        return $this->render('admin/brands/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/modifier', name: 'brands_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La marque a été modifiée ! ');
            return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/brands/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'brands_delete', methods: ['POST'])]
    public function delete(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        $this->addFlash('success', 'La marque a été supprimée ! ');
        return $this->redirectToRoute('brands_index', [], Response::HTTP_SEE_OTHER);
    }
}
