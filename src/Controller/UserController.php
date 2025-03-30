<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Entity\Cart;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'user')]
final class UserController extends AbstractController
{
    #[Route('/profile', name: '_profile')]
    public function profileAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if (is_null($user))
            return $this->redirectToRoute('security_login');

        if ($form->isSubmitted() && $form->isValid())
        {
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('user/edit_user.html.twig', [
            'editForm' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/cart', name: '_cart')]
    public function cartAction(EntityManagerInterface $manager): Response
    {
        // Récupération du panier de l'utilisateur connect
        $user = $this->getUser();
        $cart = $user->getCart() ?? [];
        $totalPrice = 0;

        foreach ($cart as $booster) {
            $totalPrice += $booster->getQuantity() * $booster->getBooster()->getPrice();
        }
        return $this->render('user/cart.html.twig', [
            'cart' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/cart/add/{id}', name: '_cart_add', methods: ['POST'])]
    public function addToCartAction(int $id, Request $request, EntityManagerInterface $manager): Response
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
}
