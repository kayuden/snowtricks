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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

#[Route('/admin/trick')]
final class TrickController extends AbstractController
{
    //trick detail modal
    #[Route('/modal/show/{id}', name: 'app_admin_trick_modal_show')]
    public function modal(Trick $trick): Response
    {
        return $this->render('admin/trick/_modal_show.html.twig', [
            'trick' => $trick,
        ]);
    }

    //trick creation
    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        //verification
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreatedAt(new DateTimeImmutable());
            $trick->setEditedAt(new DateTimeImmutable());

            //image management
            /** @var UploadedFile[] $imageFiles */
            $imageFiles = $form->get('imagePaths')->getData();

            $imagePaths = [];

            //if files uploaded
            if ($imageFiles) {
                //slugify
                $trickName = $trick->getName(); //use trick name to format
                $sluggedName = $slugger->slug($trickName)->lower(); 
                $timestamp = (new \DateTime())->format('Ymd_His'); //timestamp

                $index = 1;
                
                foreach ($imageFiles as $imageFile) {
                    $extension = $imageFile->guessExtension();
                    $newFilename = $sluggedName . '_' . $index . '_' . $timestamp . '.' . $extension; //name file

                    try {
                        //save in directory ex. /uploads/images/trickname_1_20250509_151654
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        $imagePaths[] = $newFilename;

                        if ($index === 1) {
                            $trick->setMainImage($newFilename);
                        }

                        $index++;
                    } catch (\Exception $e) { //error
                        $this->addFlash('error', 'Image upload failed.');
                    }
                }

                //saves paths in Trick entity
                $trick->setImagePaths($imagePaths);
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
    #[Route('/modal/edit/{id}', name: 'app_admin_trick_modal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setEditedAt(new \DateTimeImmutable());

            $manager->flush();
        }

        return $this->render('admin/trick/_modal_edit.html.twig', [
            'form' => $form,
            'trick' => $trick,
        ]);
    }

    //trick deletion
    #[Route('/delete/{id}', name: 'app_admin_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            $imageDir = $this->getParameter('images_directory'); //services.yaml

            //delete in dir
            foreach ($trick->getImagePaths() as $imageFileName) {
                $imagePath = $imageDir . '/' . $imageFileName;

                if ($filesystem->exists($imagePath)) {
                    try {
                        $filesystem->remove($imagePath);
                    } catch (IOExceptionInterface $exception) {
                        $this->addFlash('error', 'An error occurred while deleting an image : ' . $imageFileName);
                    }
                }
            }

            //delete entity trick
            $em->remove($trick);
            $em->flush();

            $this->addFlash('success', 'The trick has been successfully removed.');
        }

        return $this->redirectToRoute('app_homepage');
    }
}