<?php

namespace App\Controller;

use App\Entity\Schedules;
use App\Form\SchedulesType;
use App\Repository\SchedulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/horaires')]
#[IsGranted('ROLE_ADMIN')]
class SchedulesCrudController extends AbstractController
{
    #[Route('/', name: 'schedules_index', methods: ['GET'])]
    public function index(SchedulesRepository $schedulesRepository): Response
    {
        return $this->render('admin/schedules/index.html.twig', [
            'schedules' => $schedulesRepository->findAll(),
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
            $this->addFlash('success', 'Les horaires ont été modifiées ! ');
            return $this->redirectToRoute('schedules_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Les horaires n\'ont pas été modifiées');
        }

        return $this->render('admin/schedules/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }
}
