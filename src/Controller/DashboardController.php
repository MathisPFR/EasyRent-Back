<?php

namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{

    public function __construct(private PaiementRepository $paiementRepository, private BienRepository $bienRepository, private Security $security) {}


    #[Route('api/dashboard/total-paiement-annee', name: 'app_total_paiement_annee')]
    public function __invoke(Request $request)
    {

        $user = $this->security->getUser();
        $paiementAnne = $this->paiementRepository->findTotalPaiementAnnee($user);
        $paiementMois = $this->paiementRepository->findTotalPaiementMoisAnnee($user);
        $biens = $this->bienRepository->findBienbyUserActif($user);

        $bienActif = 0;
        $bienLoue = 0;
        $bienInactif = 0;

        foreach ($biens as $bien) {

            if ($bien->isActif() === false) {
                $bienInactif++;
            }
            if ($bien->isActif() == true) {
                $bienActif++;
            }
            if ($bien->isActif() == true && count($bien->getLocataires()) > 0) {
                $bienLoue++;
            }
        }

        $data = [
            "paiementAnne" => $paiementAnne,
            "paiementMois" => $paiementMois,
            "biens" => [
                "bienActif" => $bienActif,
                "bienLoue" => $bienLoue,
                "bienIncactif" => $bienInactif,
            ],

        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
