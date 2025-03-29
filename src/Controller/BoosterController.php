<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Form\BoosterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoosterController extends AbstractController
{
    #[Route('/booster', name: 'booster')]
    public function booster(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booster = new Booster();
        $form = $this->createForm(BoosterFormType::class, $booster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booster);
            $entityManager->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('Booster/booster.html.twig', [
            'BoosterForm' => $form,
        ]);
    }
}
