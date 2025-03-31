<?php

namespace App\Controller;

use App\Entity\Booster;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'product')]
final class ProductController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function listAction(EntityManagerInterface $manager): Response
    {
        $boosters = $manager->getRepository(Booster::class)->findAll();

        $user = $this->getUser();
        $cart = $user->getCart();
        $content = $cart->getCartContents()->getValues();

        $cartBoosters = array();
        foreach ($content as $cartItem) {
            $cartBoosters[$cartItem->getBooster()->getId()] = $cartItem->getQuantity();
        }

        $minQuantities = array();
        foreach ($boosters as $booster) {
            $found = array_key_exists($booster->getId(), $cartBoosters);
            $minQuantities[$booster->getId()] = $found ? -$cartBoosters[$booster->getId()] : 0;
        }

        return $this->render('Product/list.html.twig', [
            'boosters' => $boosters,
            'minQuantities' => $minQuantities,
        ]);
    }
}
