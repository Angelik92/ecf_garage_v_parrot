<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Entity\User;
use App\Form\AdsType;
use App\Form\AdsTypeEdit;
use App\Repository\AdsRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/dashboard/annonces')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AdsCrudController extends AbstractController
{
    #[Route('/', name: 'ads_index', methods: ['GET'])]
    public function index(AdsRepository $adsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $ads = $paginator->paginate(
            $adsRepository->findBy([], ['create_at'=> 'DESC']),

            $request->query->getInt('page', 1),
            6
        );

        return $this->render('admin/ads/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    #[Route('/nouveau', name: 'ads_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $ad = new Ads();

        // Create a form for the Ads entity using AdsType form type
        $form = $this->createForm(AdsType::class, $ad);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the uploaded picture files
            $pictureFiles = $form->get('pictures')->getData();

            foreach ($pictureFiles as $pictureFile) {
                $picturePath = $uploaderService->upload($pictureFile);
                $ad->addPicture($picturePath);

                // Persist Pictures entity
                $entityManager->persist($picturePath);
            };
            // Persist Ads entity
            $entityManager->persist($ad);
            $entityManager->flush();

            // Redirect to the ads index page upon successful submission
            $this->addFlash('success', 'L\'annonce a été ajoutée ! ');
            return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'L\'annonce n\'a pas été ajoutée');
        }

        return $this->render('admin/ads/new.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'ads_show', methods: ['GET'])]
    public function show(Ads $ad): Response
    {
        // Show details of a specific ad
        return $this->render('admin/ads/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    #[Route('/{id}/edit', name: 'ads_edit', methods: ['GET', 'POST'])]
    public function edit(UploaderService $uploaderService, Request $request, Ads $ad, EntityManagerInterface $entityManager): Response
    {
        // Create a form for the Ads entity using AdsTypeEdit form type
        $form = $this->createForm(AdsTypeEdit::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the updated picture files
            $pictureFiles = $form->get('pictures')->getData();

            foreach ($pictureFiles as $pictureFile) {
                // Upload and associate new pictures with the ad
                $picturePath = $uploaderService->upload($pictureFile);
                $ad->addPicture($picturePath);

                // Persist Pictures entity
                $entityManager->persist($picturePath);
            }

            // Update the existing Ads entity
            $entityManager->flush();

            // Redirect to the ads index page upon successful submission
            $this->addFlash('success', 'L\'annonce  a été modifiée ! ');
            return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()){
            // Show an error message if the form is not valid
            $this->addFlash('danger', 'L\'annonce n\'a pas été modifiée');
        }

        return $this->render('admin/ads/edit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ads_delete', methods: ['POST'])]
    public function delete(Request $request, Ads $ad, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            // Retrieve picture files associated with the ad
            $pictureFiles = $ad->getPictures();

            $filesystem= new Filesystem();
            // Remove the associated picture files from the filesystem
            foreach ($pictureFiles as $picture) {
                $picturePath = $this->getParameter('uploads.folder').'/'.$picture->getPath();
                if ($filesystem->exists($picturePath)) {
                    $filesystem->remove($picturePath);
                }
            }

            // Delete the ad entity from the database
            $entityManager->remove($ad);
            $entityManager->flush();
        }
        // Redirect to the ads index page upon successful deletion
        $this->addFlash('success', 'L\'annonce  a été supprimée ! ');
        return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
    }
}
