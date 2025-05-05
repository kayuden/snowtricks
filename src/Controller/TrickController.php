<?php

namespace App\Controller;

use App\Entity\Trick;
use DateTimeImmutable;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/trick')]
final class TrickController extends AbstractController
{
    // trick creation
    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new DateTimeImmutable());
            $trick->setEditedAt(new DateTimeImmutable());
            $trick->setImagePath('image1');

            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/trick/new.html.twig', [
            'form' => $form,
        ]);
    }

    // trick modification
    #[Route('/edit/{id}', name: 'app_admin_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setEditedAt(new \DateTimeImmutable());

            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/trick/edit.html.twig', [
            'form' => $form,
            'trick' => $trick,
        ]);
    }

    //trick deletion
    #[Route('/delete/{id}', name: 'app_admin_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $em->remove($trick);
            $em->flush();
            $this->addFlash('success', 'Le trick a bien été supprimé.');
        }

        return $this->redirectToRoute('app_home');
    }
}
