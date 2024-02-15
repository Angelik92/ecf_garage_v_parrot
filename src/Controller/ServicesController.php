<?php

namespace App\Controller;

use App\Entity\Services;
use App\Form\ServicesInfoType;
use App\Repository\ServicesRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/services', name: 'services')]
    public function index(Request $request, Services $service, ServicesRepository $servicesRepository, MailerService $mailerService): Response
    {
        $services = $servicesRepository->findAll();

        $form = $this->createForm(ServicesInfoType::class, $service);
        $form->get('message')->setData('Bonjour. Je souhaite obtenir des informations complémentaires concernant le service ' . $service->getTitle() . ' Cordialement.');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $customerEmail = $form->get('customerEmail')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();

            $mailerService->sendMail('garage@gvp.fr', 'contact@gvp.fr', $subject, '@email_templates/service_info.html.twig', [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'customerEmail' => $customerEmail,
                'phoneNumber' => $phoneNumber,
                'message' => $message,

            ]);

            return $this->redirectToRoute('services', [], Response::HTTP_SEE_OTHER);
            $this->addFlash('success', 'Votre message a été envoyé ! ');
            // If the form was submitted but is not valid, add an error flash message
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Attention ! Votre message n\'a pas été envoyé. ');
        }


        return $this->render('pages/services/services.html.twig', [
            "services" => $services,
            'service' => $service,
            'form' => $form,
        ]);
    }
}
