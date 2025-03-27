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

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $boosterId = $request->request->get('boosters_id');
        $quantity = (int) $request->request->get('quantite');

        if ($quantity === 0) {
            return $this->redirectToRoute('list');
        }

        $booster = $manager->getRepository(Booster::class)->find($boosterId);
        if (!$booster) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        // Vérifie si l'utilisateur a déjà cet article dans son panier
        $cart = $user->getCart();

        if ($cart) {
            // Mise à jour de la quantité
            $newQuantity = $cart->getQuantity() + $quantity;

            if ($newQuantity <= 0) {
                // Si la nouvelle quantité est 0 ou négative, on supprime l'article du panier
                $manager->remove($cart);
            } else {
                $cart->setQuantity($newQuantity);
            }
        } else {
            // Création d'un nouvel article dans le panier
            if ($quantity > 0) {
                $cart = new Cart();
                $cart->setUser($user);
                $cart->addContent($booster);
                $manager->persist($cart);
            }
        }
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
