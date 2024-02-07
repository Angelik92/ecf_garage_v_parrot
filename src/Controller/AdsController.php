<?php

namespace App\Controller;

use App\Data\FiltersData;

use App\Entity\Ads;
use App\Form\AdsContactType;
use App\Form\FiltersAdsType;
use App\Repository\AdsRepository;
use App\Services\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdsController extends AbstractController
{
    #[Route('/annonces', name: 'ads')]
    public function index(AdsRepository $adsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = new FiltersData();
        $data->page =$request->get('page', 1);
        $form = $this->createForm(FiltersAdsType::class, $data);
        $form->handleRequest($request);
        $ads = $paginator->paginate(
            $adsRepository->filtersSearch($data),
            $request->query->getInt('page', 1),
            3
        );
        if($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('pages/ads/partials/_ads_cards.html.twig', ['ads' => $ads]),
                'form' => $this->renderView('pages/ads/partials/_filters.html.twig', ['form'=> $form])
            ]);
        }

        return $this->render('pages/ads/ads.html.twig', [
            'ads' => $ads,
            'form' => $form->createView()
        ]);
    }


    #[Route('/annonces/{id}', name: 'ads_info', methods: ['GET','POST'])]
    public function info(Ads $ad, Request $request, MailerService $mailerService): Response
    {
      
            $form = $this->createForm(AdsContactType::class, $ad);
            $form->get('comment')->setData('Bonjour. Je souhaite avoir des informations complémentaires concernant le véhicule ' . $ad->getCar() . ' Cordialement.');
            $form -> handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $firstname= $form->get('firstname')->getData();
                $lastname = $form->get('lastname')->getData();
                $customerEmail = $form->get('customerEmail')->getData();
                $phoneNumber = $form->get('phoneNumber')->getData();
                $subject = $form->get('subject')->getData();
                $comment = $form->get('comment')->getData();

                $mailerService->sendMail('garage@gvp.fr', 'contact@gvp.fr', $subject, '@email_templates/ad_info.html.twig', [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'customerEmail' => $customerEmail,
                    'phoneNumber' => $phoneNumber,
                    'comment' => $comment

                ]);
                $this->addFlash('success', 'Votre message a été envoyé ! ');
                return $this->redirectToRoute('ads', [], Response::HTTP_SEE_OTHER);
                // If the form was submitted but is not valid, add an error flash message
            } else if($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Attention ! Votre message n\'a pas été envoyé. ');
            }

            return $this->render('pages/ads/info.html.twig', [
                'ad' => $ad,
                'form' => $form
            ]);
        }

}
