<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Entity\Cart;
use App\Entity\CartContent;
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
        $user = $this->getUser();
        $cart = $user->getCart();
        $cartContents = $cart->getCartContents()->getValues();
        $totalPrice = 0;

        foreach ($cartContents as $content) {
            $totalPrice += $content->getQuantity() * $content->getBooster()->getPrice();
        }

        return $this->render('user/cart.html.twig', [
            'cart' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/cart/delete/{id}', name: '_cart_delete', requirements: ['id' => '[1-9]\d*'])]
    public function deleteFromCart(EntityManagerInterface $manager, int $id): Response
    {
        $cartContent = $manager->getRepository(CartContent::class)->find($id);
        if ($this->getUser() !== $cartContent->getCart()->getUser())
            return $this->redirectToRoute('cart');
        $quantity = $cartContent->getQuantity();
        $booster = $cartContent->getBooster();
        $booster->setStock($booster->getStock() + $quantity);
        $manager->remove($cartContent);
        $manager->flush();

        return $this->redirectToRoute('user_cart');
    }

    #[Route('/cart/add/{id}', name: '_cart_add', requirements: ['id' => '[1-9]\d*'])]
    public function addToCartAction(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $booster = $manager->getRepository(Booster::class)->find($id);
        if (is_null($booster)) {
            throw $this->createNotFoundException("Ce produit n'existe pas.");
        }

        $cart = $user->getCart();

        $quantity = $request->request->get('quantite');

        $cartContent = $manager->getRepository(CartContent::class)->findOneBy(['booster' => $booster->getId(), 'cart' => $cart->getId()]);
        if (!is_null($cartContent)) {
            $cartContent->setQuantity($cartContent->getQuantity() + $quantity);
            if ($cartContent->getQuantity() <= 0)
                $cart->removeCartContent($cartContent);
        } else {
            $content = new CartContent();
            $content
                ->setBooster($booster)
                ->setQuantity($quantity)
                ->setCart($cart);
            $manager->persist($content);
            $cart->addCartContent($content);
        }

        $booster->setStock($booster->getStock() - $quantity);
        $manager->flush();

        return $this->redirectToRoute('user_cart');
    }

    #[Route('/cart/clear', name: '_cart_clear')]
    public function cartClearAction(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $cartContents = $cart->getCartContents()->getValues();

        foreach ($cartContents as $cartContent) {
            $quantity = $cartContent->getQuantity();
            $booster = $cartContent->getBooster();
            $booster->setStock($booster->getStock() + $quantity);
            $cart->removeCartContent($cartContent);
        }

        $manager->flush();

        return $this->redirectToRoute('user_cart');
    }

    #[Route('/cart/order', name: '_cart_order')]
    public function cartOrderAction(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $cartContents = $cart->getCartContents()->getValues();

        foreach ($cartContents as $cartContent) {
            $cart->removeCartContent($cartContent);
        }

        $manager->flush();

        return $this->redirectToRoute('user_cart');
    }
}
