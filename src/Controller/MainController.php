<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function cart(EntityManagerInterface $manager): Response
    {
        // Récupération du panier de l'utilisateur connect
        $user = $this->getUser();
        $cart = $user->getCart() ?? [];
        $totalPrice = 0;

        foreach ($cart as $booster) {
            $totalPrice += $booster->getQuantity() * $booster->getBooster()->getPrice();
        }
        return $this->render('Cart/cart.html.twig', [
            'cart' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function addToCart(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $booster = $manager->getRepository(Booster::class)->find($id);
        if (!$booster) {
            throw $this->createNotFoundException("Ce produit n'existe pas.");
        }

        $cart = $user->getCart();
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $manager->persist($cart);
        }

        // Récupère la quantité envoyée par le formulaire
        $quantity = (int) $request->request->get('quantite', 1);

        $cart->addContent($booster, $quantity);
        $manager->persist($cart);
        $manager->flush();

        return $this->redirectToRoute('cart');
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
