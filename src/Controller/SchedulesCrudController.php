<?php

namespace App\Controller;

use App\Entity\Schedules;
use App\Form\SchedulesType;
use App\Repository\SchedulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/horaires')]
class SchedulesCrudController extends AbstractController
{
    #[Route('/', name: 'schedules_index', methods: ['GET'])]
    public function index(SchedulesRepository $schedulesRepository): Response
    {
        return $this->render('admin/schedules/index.html.twig', [
            'schedules' => $schedulesRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'schedules_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $schedule = new Schedules();
        $form = $this->createForm(SchedulesType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($schedule);
            $entityManager->flush();

            return $this->redirectToRoute('schedules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/schedules/new.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'schedules_show', methods: ['GET'])]
    public function show(Schedules $schedule): Response
    {
        return $this->render('admin/schedules/show.html.twig', [
            'schedule' => $schedule,
        ]);
    }

    #[Route('/{id}/edit', name: 'schedules_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Schedules $schedule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SchedulesType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('schedules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/schedules/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'schedules_delete', methods: ['POST'])]
    public function delete(Request $request, Schedules $schedule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $schedule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($schedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('schedules_index', [], Response::HTTP_SEE_OTHER);
    }
}
