<?php

namespace App\Controller;

use App\Entity\Booster;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('Main/index.html.twig');
    }

    #[Route('/cart', name: 'cart')]
    public function cart(): Response
    {
        return $this->render('Cart/cart.html.twig');
    }

    #[Route('/list', name: 'list')]
    public function list(EntityManagerInterface $manager): Response
    {
        // Récupération de tous les boosters en base de données
        $boosters = $manager->getRepository(Booster::class)->findAll();

        return $this->render('List/list.html.twig', [
            'boosters' => $boosters
        ]);
    }
}
