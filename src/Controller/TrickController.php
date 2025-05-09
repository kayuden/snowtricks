<?php

namespace App\Controller;

use App\Entity\Trick;
use DateTimeImmutable;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/trick')]
final class TrickController extends AbstractController
{
    // trick creation
    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new DateTimeImmutable());
            $trick->setEditedAt(new DateTimeImmutable());

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                // slugify
                $trickName = $trick->getName(); // empty ?
                $sluggedName = $slugger->slug($trickName)->lower(); // apply trick name to rename

                // timestamp
                $timestamp = (new \DateTime())->format('Ymd_His');

                // incrementation
                $existingImages = glob($this->getParameter('images_directory') . '/' . $sluggedName . '_*');
                $index = count($existingImages) + 1; // start to 1

                // rename
                $extension = $imageFile->guessExtension();
                $newFilename = $sluggedName . '_' . $index . '_' . $timestamp . '.' . $extension;

                // move file
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $trick->setImagePath($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'L\'upload de l\'image a échoué.');
                    return $this->redirectToRoute('app_admin_trick_new');
                }
            }

            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('admin/trick/new.html.twig', [
            'form' => $form->createView(),
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

            return $this->redirectToRoute('app_homepage');
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

        return $this->redirectToRoute('app_homepage');
    }

    //trick detail
    #[Route('/{id}', name: 'app_admin_trick_show', methods: ['GET'])]
    public function show(?Trick $trick): Response
    {
        return $this->render('admin/trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }
}