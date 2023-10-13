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
    public function index(CarsRepository $carsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Paginate and display a list of cars ordered by brand in ascending order
        $cars = $paginator->paginate(
          $carsRepository->findBy([], ['brand'=>'ASC']),
          $request->query->getInt('page', 1),
          8

        );

        return $this->render('admin/cars/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/nouveau', name: 'cars_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new car model and handle the form submission
        $car = new Cars();
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new car model and show a success message
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Le modèle a été ajouté ! ');
            return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'Le modèle n\'a pas été ajouté');
        }

        return $this->render('admin/cars/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cars_show', methods: ['GET'])]
    public function show(Cars $car): Response
    {
        // Display the details of a car model
        return $this->render('admin/cars/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/modifier', name: 'cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        // Edit an existing car model and handle the form submission
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Persist the changes and show a success message
            $this->addFlash('success', 'Le modèle a été modifié ! ');
            return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'Le modèle n\'a pas été modifié');
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
            // Delete the car model
            $entityManager->remove($car);
            $entityManager->flush();
        }

        // Show a success message and redirect to the car model list
        $this->addFlash('success', 'Le modèle a été supprimé ! ');
        return $this->redirectToRoute('cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
