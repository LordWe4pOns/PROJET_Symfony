<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\DatabaseHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                $content = $cart->getCartContents()->getValues();
                for ($i = 0; $i < count($content); $i++)
                {
                    $content[$i]->setStock($content[$i]->getStock() + 1);
                }
                $entityManager->remove($cart);
            }
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_gestion_clients');
    }
}
