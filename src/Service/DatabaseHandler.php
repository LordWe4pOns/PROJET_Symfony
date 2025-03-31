<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseHandler
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getCartAmount(int $id): int
    {
        $user = $this->em->getRepository(User::class)->find($id);
        $cart = $user->getCart();
        $content = $cart->getCartContents()->getValues();
        $total = 0;
        foreach ($content as $item) {
            $total += $item->getQuantity();
        }

        return $total;
    }
}