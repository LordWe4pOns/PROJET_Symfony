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
    public function indexAction(): Response
    {
        $user = $this->getUser();
        return $this->render('Main/index.html.twig');
    }

    public function baseAction(): Response
    {
        $user = $this->getUser();
        $roles = $user ? $user->getRoles() : [];

        return $this->render('base.html.twig', [
            'roles' => $roles
        ]);
    }
}
