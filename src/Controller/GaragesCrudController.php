<?php

namespace App\Controller;

use App\Entity\Garages;
use App\Form\GaragesType;
use App\Repository\GaragesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/informations')]
#[IsGranted('ROLE_ADMIN')]
class GaragesCrudController extends AbstractController
{
    #[Route('/', name: 'garages_index', methods: ['GET'])]
    public function index(GaragesRepository $garagesRepository): Response
    {
        return $this->render('admin/garages/index.html.twig', [
            'garages' => $garagesRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'garages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $garage = new Garages();
        $form = $this->createForm(GaragesType::class, $garage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($garage);
            $entityManager->flush();

            return $this->redirectToRoute('garages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/garages/new.html.twig', [
            'garage' => $garage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'garages_show', methods: ['GET'])]
    public function show(Garages $garage): Response
    {
        return $this->render('admin/garages/show.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/{id}/edit', name: 'garages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Garages $garage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GaragesType::class, $garage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('garages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/garages/edit.html.twig', [
            'garage' => $garage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'garages_delete', methods: ['POST'])]
    public function delete(Request $request, Garages $garage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$garage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($garage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('garages_index', [], Response::HTTP_SEE_OTHER);
    }
}
