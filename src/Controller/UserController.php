<?php

namespace App\Controller;

use App\Form\EditUserType;
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
}
