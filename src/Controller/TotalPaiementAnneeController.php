<?php

namespace App\Controller;

use App\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TotalPaiementAnneeController extends AbstractController{

    public function __construct(private PaiementRepository $paiementRepository, private Security $security) {}
    
    
    #[Route('api/dashboard/total-paiement-annee', name: 'app_total_paiement_annee')]
    public function __invoke(Request $request)
    {

        $user = $this->security->getUser();
        $paiementAnne = $this->paiementRepository->findTotalPaiementAnnee($user);

    $paiementMois = $this->paiementRepository->findTotalPaiementMoisAnnee($user);

        $data = [
            "paiementAnne" => $paiementAnne,
            "paiementMois" => $paiementMois,
            
        ];

        return new JsonResponse($data, Response::HTTP_OK);

    }
}
