<?php

namespace App\Controller;

use App\Data\FiltersData;

use App\Form\FiltersAdsType;
use App\Repository\AdsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
