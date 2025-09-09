<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route(path: '/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            //verification token
            $token = Uuid::v4()->toRfc4122();
            $user->setVerificationToken($token);
            $user->setIsVerified(false);

            $entityManager->persist($user);
            $entityManager->flush();

            //verification url
            $verificationUrl = $urlGenerator->generate('app_verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            //email
            $email = (new Email())
                ->from('no-reply@snowtrick.org')
                ->to($user->getEmail())
                ->subject('Confirm your email address')
                ->html("
                    <p>Welcome to Snowtricks !</p>
                    <p>Please click this link to activate your account:</p>
                    <p><a href='$verificationUrl'>Activate my account</a></p>
                ");

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error sending confirmation email.');
                return $this->redirectToRoute('app_register');
            }

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationType' => $form,
        ]);
    }

    #[Route(path: '/verify/email', name: 'app_verify_email', methods: ['GET'])]
    public function verifyEmail(string $token, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Invalid confirmation link.');
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $em->flush();

        $this->addFlash('success', 'Your account is now activated. You can sign in.');

        return $this->redirectToRoute('app_login');
    }

}
