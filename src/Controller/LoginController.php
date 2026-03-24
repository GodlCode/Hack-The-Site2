<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends AbstractController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private EntityManagerInterface $em
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request,UserRepository $userRepository, RateLimiterFactory $loginAttemptLimiter): Response {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
            $user = $userRepository->findByEmailUnsafe($email);

            $limiter = $loginAttemptLimiter->create($request->getClientIp() . '-' . $email);
            $limit = $limiter->consume(1);
            if (!$limit->isAccepted()) {
                $error = 'Too many login attempts. Try again later.';
                return $this->render('login/login.html.twig', [
                    'last_username' => $email,
                    'error' => $error,
                ]);
            }

            if ($user) {
                if ($user->isLocked()) {
                    $error = 'Account is temporarily locked. Try again later.';
                } 
                elseif (!password_verify($password, $user->getPassword())) {
                    $user->incrementFailedAttempts();
                    if ($user->getFailedAttempts() >= 3) {
                        $user->lockUntil(new \DateTimeImmutable('+30 minutes'));
                    }
                    $this->em->flush(); 
                    $error = 'Invalid credentials.';
                } else {
                    $user->resetFailedAttempts();
                    $this->em->flush();

                    $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
                    $this->tokenStorage->setToken($token);
                    $request->getSession()->set('_security_main', serialize($token));
                    $request->getSession()->migrate(true); // prevent session fixation
                    return $this->redirectToRoute('app_home');
                }
            } else {
                $error = 'Invalid credentials.';
            }
        }

        return $this->render('login/login.html.twig', [
            'last_username' => '',
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
