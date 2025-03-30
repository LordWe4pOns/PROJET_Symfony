<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Form\BoosterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'product')]
final class ProductController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function listAction(EntityManagerInterface $manager): Response
    {
        // Récupération de tous les boosters en base de données
        $boosters = $manager->getRepository(Booster::class)->findAll();

        return $this->render('Product/list.html.twig', [
            'boosters' => $boosters
        ]);
    }

    #[Route('/add/booster', name: '_add_booster')]
    public function boosterAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booster = new Booster();
        $form = $this->createForm(BoosterFormType::class, $booster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booster);
            $entityManager->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('Product/booster.html.twig', [
            'BoosterForm' => $form,
        ]);
    }
}
