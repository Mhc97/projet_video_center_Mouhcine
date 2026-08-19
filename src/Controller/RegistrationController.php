<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;


class RegistrationController extends AbstractController
{
    public function __construct(private EmailVeriier $emailVerifier){

    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $PasswordHasher, EntityManagerInterface $em): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('app_home');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword($PasswordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );

            $em->persist($user);
            $em->flush();

        $this->emailVerifier->sendEmailConfirmation(' app_verifiy_email', $user,
            (new TemplatedEmail())
                ->from(new Adress('noreply@videocenter.be', 'Video Center')))
                ->to($user->getEmail())
                ->subject('Confimez votre email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                

       $this->addFlash('info', 'Un email de confirmation a été envoyé.');
       return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);

        $this->addFlash('success', 'Inscription réussie ! Vérifiez votre email pour activer votre compte.');
        return $this->redirectToRoute('app_login');
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        try {
          $this->emailVerifier->handleEmailConfimation($request, $user);
          $this->addFlash('success', 'Email vérifié !');  
        } catch (VerifyEmailExceptionInterface $e){
            $this->addFlash('error', 'Lien invalide ou expiré. ');
        }
        return $this->redirectToRoute('app_home');
    }
}
