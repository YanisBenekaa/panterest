<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET", "POST", "PUT"})
     */
    public function edit(Request $request, EntityManagerInterface $em, FlashyNotifier $flashy): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $flashy->success('Account successful edited !');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/change-password", name="app_account_change_password", methods={"GET", "POST"})
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHashed, FlashyNotifier $flashy): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHashed->hashPassword($user, $form['plainPassword']->getData())
            );

            $em->flush();

            $flashy->success('Password successful edited !');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
