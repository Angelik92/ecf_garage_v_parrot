<?php

namespace App\Controller;

use App\Entity\Schedules;
use App\Form\ContactType;
use App\Repository\GaragesRepository;
use App\Repository\SchedulesRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationsController extends AbstractController
{
    #[Route('/informations', name: 'informations')]
    public function index(GaragesRepository $garagesRepository, SchedulesRepository $schedulesRepository, Request $request, MailerService $mailerService): Response
    {
        $garages = $garagesRepository->findAll();
        $schedules = $schedulesRepository->findAll();

        $form = $this->createForm(ContactType::class);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->get('service')->getData();
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $customerEmail = $form->get('customerEmail')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();

            $mailerService->sendMail('garage@gvp.fr', 'contact@gvp.fr', $subject, '@email_templates/contact.html.twig', [
                'service' => $service,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'customerEmail' => $customerEmail,
                'phoneNumber' => $phoneNumber,
                'message' => $message,

            ]);

            $this->addFlash('success', 'Votre message a été envoyé ! ');
            return $this->redirectToRoute('informations', [], Response::HTTP_SEE_OTHER);
            // If the form was submitted but is not valid, add an error flash message
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Attention ! Votre message n\'a pas été envoyé. ');
        }


        return $this->render('pages/informations/informations.html.twig', [
            'garages' => $garages,
            'schedules' => $schedules,
            'form' => $form,
        ]);
    }
}
