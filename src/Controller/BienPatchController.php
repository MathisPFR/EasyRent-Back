<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class BienPatchController extends AbstractController
{
    public function __construct(
        private BienRepository $bienRepository, 
        private Security $security,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer
    ) {}

    #[Route('api/biens/{id}', name: 'app_bien_patch', methods: ['PATCH'])]
    public function __invoke( 
    
        int $id,
        Request $request,
        BienRepository $bienRepository,
        EntityManagerInterface $entityManager,
        Security $security,
        NormalizerInterface $normalizer
    ): JsonResponse {
        // Récupérer le bien
        $bien = $bienRepository->find($id);
    
        if (!$bien) {
            return new JsonResponse(['message' => 'Bien non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        // Vérifier si l'utilisateur actuel est le propriétaire
        $currentUser = $security->getUser();
        if ($bien->getUsers() !== $currentUser) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas autorisé à modifier ce bien'], Response::HTTP_FORBIDDEN);
        }
    
        // Vérifier si le bien a des locataires
        $hasLocataires = count($bien->getLocataires()) > 0;
    
        // Décoder le JSON de la requête
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['message' => 'JSON invalide'], Response::HTTP_BAD_REQUEST);
        }
    
        // Vérifier chaque champ et l'appliquer
        if (isset($data['titre'])) {
            $bien->setTitre($data['titre']);
        }
        if (isset($data['adresse'])) {
            $bien->setAdresse($data['adresse']);
        }
        if (isset($data['surface'])) {
            $bien->setSurface($data['surface']);
        }
        if (isset($data['type'])) {
            $bien->setType($data['type']);
        }
        if (isset($data['loyer'])) {
            $bien->setLoyer($data['loyer']);
        }
    
        // Vérifier si on veut modifier `actif` et si c'est autorisé
        if (isset($data['actif'])) {
            if ($hasLocataires) {
                return new JsonResponse(['message' => 'Impossible de modifier "actif" car des locataires sont présents'], Response::HTTP_FORBIDDEN);
            }
            $bien->setActif($data['actif']);
        }
    
        // Enregistrer les modifications
        $entityManager->flush();
    
        // Normalisation pour éviter les problèmes de sérialisation
        $bienNormalized = $normalizer->normalize($bien, null, ['groups' => 'bien:read']);
    
        return new JsonResponse($bienNormalized, Response::HTTP_OK);
    }
}