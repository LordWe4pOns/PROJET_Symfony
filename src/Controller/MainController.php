<?php

namespace App\Controller;

use App\Service\DatabaseHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function indexAction(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('success', 'Vous avez été déconnecté avec succès.');
        }

        $id = -1;
        $fullname = 'Client anonyme';
        $country = 'contrées inconnues';

        if ($user){
            $id = $user->getId();
            $fullname = $user->getName() . ' ' . $user->getSurname();
            $country = $user->getCountry() ? $user->getCountry()->getName() : 'contrées inconnues';
        }

        $handler = new DatabaseHandler($entityManager);
        $role = $handler->getMainRole($id);

        return $this->render('Main/index.html.twig', [
            'fullname' => $fullname,
            'country' => $country,
            'role' => $role,
        ]);
    }

    public function menuAction(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $id = is_null($user) ? null : $user->getId();

        $handler = new DatabaseHandler($entityManager);
        $amount = $id ? $handler->getCartAmount($id) : '';

        return $this->render('Layouts/_menu.html.twig', ['amount' => $amount]);
    }

}