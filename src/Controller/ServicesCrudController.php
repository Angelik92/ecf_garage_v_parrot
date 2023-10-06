<?php

namespace App\Controller;

use App\Entity\Services;
use App\Form\ServicesType;
use App\Repository\ServicesRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/services')]
#[IsGranted('ROLE_ADMIN')]
class ServicesCrudController extends AbstractController
{
    #[Route('/', name: 'services_index', methods: ['GET'])]
    public function index(ServicesRepository $servicesRepository): Response
    {
        return $this->render('admin/services/index.html.twig', [
            'services' => $servicesRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'services_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $service = new Services();
        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('pictureFile')->getData();
                $picturePath = $uploaderService->upload($pictureFile);
                $service->setPictureFile($picturePath);
                $entityManager->persist($picturePath);

            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', 'Le service a été ajouté ! ');
            return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/services/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'services_show', methods: ['GET'])]
    public function show(Services $service): Response
    {
        return $this->render('admin/services/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/modifier', name: 'services_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Services $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le service a été modifié ! ');
            return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/services/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'services_delete', methods: ['POST'])]
    public function delete(Request $request, Services $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $pictureFile= $service ->getPictureFile();

            $filesystem = new Filesystem();
            $picturePath = $this->getParameter('uploads.folder').'/'. $pictureFile->getPath();
            $filesystem->remove($picturePath);

            $entityManager->remove($service);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Le service a été supprimé ! ');
        return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
    }
}
