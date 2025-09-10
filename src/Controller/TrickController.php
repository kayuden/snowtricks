<?php

namespace App\Controller;

use App\Entity\Trick;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/trick')]
final class TrickController extends AbstractController
{
    //trick detail
    #[Route('/show/{id}', name: 'app_trick_show', methods: ['GET', 'POST'])]
    public function show(Trick $trick,Request $request,EntityManagerInterface $em): Response {
        $commentForm = null;

        if ($this->getUser()) {
            $comment = new Comment();
            $comment->setTrick($trick);
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());

            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('app_trick_show', ['id' => $trick->getId()]);
            }
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comment_form' => $commentForm ? $commentForm->createView() : null,
        ]);
    }


    //trick creation
    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, TrickRepository $trickRepository): Response
    {
        $trick = new Trick();

        if (empty($trick->getVideoEmbeds())) {
            $trick->setVideoEmbeds(['']);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        //verification
        if ($form->isSubmitted()) {
            $existingTrick = $trickRepository->findOneBy(['name' => $trick->getName()]);
            if ($existingTrick) {
                $this->addFlash('danger', 'A trick with this name already exists.');

                return $this->redirectToRoute('app_homepage');
            }
            
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

                $this->addFlash('success', 'The trick has been successfully added.');

                return $this->redirectToRoute('app_homepage');
            }
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // trick modification
    #[Route('/edit/{id}', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Trick $trick,EntityManagerInterface $em,SluggerInterface $slugger
    ): Response {
        
        if (count($trick->getVideoEmbeds() ?? []) === 0) {
            $trick->setVideoEmbeds(['']);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setEditedAt(new \DateTimeImmutable());

            /** @var UploadedFile[]|null $imageFiles */
            $imageFiles = $form->get('imagePaths')->getData();
            $imagesDir  = $this->getParameter('images_directory');

            if ($imageFiles) {
                $slug = $slugger->slug($trick->getName())->lower();
                $timestamp = (new \DateTime())->format('Ymd_His');

                $existing = $trick->getImagePaths() ?? [];
                $index = count($existing) + 1;

                foreach ($imageFiles as $file) {
                    $ext = $file->guessExtension() ?: 'bin';
                    $new = sprintf('%s_%d_%s.%s', $slug, $index, $timestamp, $ext);
                    $file->move($imagesDir, $new);
                    $existing[] = $new;

                    if (!$trick->getMainImage()) {
                        $trick->setMainImage($new);
                    }
                    $index++;
                }
                $trick->setImagePaths($existing);
            }

            $em->flush();

            $this->addFlash('success', 'The trick has been successfully updated');
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        return $this->render('trick/edit.html.twig', [
            'form'  => $form->createView(),
            'trick' => $trick,
        ]);
    }

    //set main image
    #[Route('/{id}/image/main', name: 'app_trick_image_set_main', methods: ['POST'])]
    public function setMainImage(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $filename = $request->request->get('filename');
        if (!$filename || !in_array($filename, $trick->getImagePaths() ?? [], true)) {
            $this->addFlash('danger', 'Image not found');
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        $trick->setMainImage($filename);
        $trick->setEditedAt(new \DateTimeImmutable());
        $em->flush();

        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
    }

    //delete an image
    #[Route('/{id}/image/delete', name: 'app_trick_image_delete', methods: ['POST'])]
    public function deleteImage(
        Request $request,
        Trick $trick,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isCsrfTokenValid('delete_image_'.$trick->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $filename   = $request->request->get('filename');
        $images     = $trick->getImagePaths() ?? [];
        $imagesDir  = $this->getParameter('images_directory');

        if ($filename && in_array($filename, $images, true)) {
            $path = $imagesDir.'/'.$filename;
            if (is_file($path)) {
                @unlink($path);
            }
            
            $images = array_values(array_filter($images, fn($i) => $i !== $filename));
            $trick->setImagePaths($images);

            if ($trick->getMainImage() === $filename) {
                $trick->setMainImage($images[0] ?? null);
            }

            $trick->setEditedAt(new \DateTimeImmutable());
            $em->flush();
        }

        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
    }

    //delete a video
    #[Route('/{id}/video/delete', name: 'app_trick_video_delete', methods: ['POST'])]
    public function deleteVideo(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isCsrfTokenValid('delete_video_'.$trick->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $index = (int) $request->request->get('index');
        $videos = $trick->getVideoEmbeds() ?? [];

        if (isset($videos[$index])) {
            unset($videos[$index]);
            $trick->setVideoEmbeds(array_values($videos));
            $trick->setEditedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('info', 'Vidéo deleted');
        }

        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
    }


    //trick deletion
    #[Route('/delete/{id}', name: 'app_trick_delete', methods: ['POST'])]
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