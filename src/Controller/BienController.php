<?php

namespace App\Controller;

use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BienController extends AbstractController
{

    public function __construct(private BienRepository $bienRepository, private Security $security) {}

    #[Route('/api/biens/recherche/{id}', name: 'app_recherche_bien')]
    public function __invoke(Request $request)
    {



        $adresse = $request->attributes->get('id');
        $user = $this->security->getUser();
        $biens = $this->bienRepository->findByAdresseAndUser($adresse, $user);


        $biensArray = array_map(function ($bien) {
            return [
                'id' => $bien->getId(),
                'titre' => $bien->getTitre(),
                'adresse' => $bien->getAdresse(),
                'surface' => $bien->getSurface(),
                'type' => $bien->getType(),
                'loyer' => $bien->getLoyer(),
            ];
        }, $biens);


        return new JsonResponse($biensArray);
    }
}
