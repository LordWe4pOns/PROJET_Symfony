<?php

namespace App\Controller;

use App\Entity\Booster;
use App\Entity\User;
use App\Form\BoosterFormType;
use App\Service\DatabaseHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin')]
final class AdminController extends AbstractController
{
    #[Route('/gestion/clients', name: '_gestion_clients')]
    public function gestionClientsAction(EntityManagerInterface $entityManager): Response
    {
        $clients = $entityManager->getRepository(User::class)->findAll();

        $handler = new DatabaseHandler($entityManager);

        foreach ($clients as $client) {
            $client->mainRole = $handler->getMainRole($client->getId());
        }

        return $this->render('Admin/clients.html.twig', ['clients' => $clients]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '[1-9]\d*'])]
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (is_null($user))
            throw $this->createNotFoundException('erreur suppression client ' . $id . ' : le client n\'existe pas');

        if ($this->getUser() !== $user && !in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles()))
        {
            $cart = $user->getCart();
            if (!is_null($cart))
            {
                $cartContents = $cart->getCartContents()->getValues();

                foreach ($cartContents as $cartContent) {
                    $quantity = $cartContent->getQuantity();
                    $booster = $cartContent->getBooster();
                    $booster->setStock($booster->getStock() + $quantity);
                    $cart->removeCartContent($cartContent);
                }
                
                $entityManager->remove($cart);
            }
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_gestion_clients');
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

            return $this->redirectToRoute('main');
        }

        return $this->render('Product/booster.html.twig', [
            'BoosterForm' => $form,
        ]);
    }
}
