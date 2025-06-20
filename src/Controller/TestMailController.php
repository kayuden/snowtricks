<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestMailController extends AbstractController
{
    #[Route('/test-mail', name: 'app_test_mail')]
    public function sendTestMail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('destinataire@example.com')
            ->subject('Test d’envoi d’email avec Symfony 7.3')
            ->text('Ceci est un email de test envoyé depuis Symfony 7.3 !')
            ->html('<p>Ceci est un <strong>email de test</strong> envoyé depuis Symfony 7.3 !</p>');

        try {
            $mailer->send($email);
            return new Response('Email envoyé avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l’envoi de l’email : ' . $e->getMessage());
        }
    }
}
