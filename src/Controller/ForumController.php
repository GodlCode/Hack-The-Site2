<?php

namespace App\Controller;

use App\Form\ForumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ForumType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forum = $form->getData();
            $forum->setUser($user);
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum');
        }

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'form' => $form,
        ]);
    }
}
