<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/modele')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CarsCrudController extends AbstractController
{
    #[Route('/', name: 'cars_index', methods: ['GET'])]
    public function index(CarsRepository $carsRepository, PaginatorInterface $paginator): Response
    {

        return $this->render('admin/cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'cars_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Cars();
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Le modèle a été ajouté ! ');
            return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/cars/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cars_show', methods: ['GET'])]
    public function show(Cars $car): Response
    {

        return $this->render('admin/cars/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/modifier', name: 'cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le modèle a été modifié ! ');
            return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/cars/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le modèle a été supprimé ! ');
        return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
