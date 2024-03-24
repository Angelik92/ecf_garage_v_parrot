<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordController extends AbstractController
{

    #[Route('/reset_password_edit/{token}', name: 'reset_password_edit/{token}', methods: ['GET', 'POST'])]
    public function resetPassword(RateLimiterFactory $passwordRecoveryLimiter, Request $request, string $token, ResetPasswordRepository $resetPasswordRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        // Create a rate limiter for password recovery requests
        $limiter = $passwordRecoveryLimiter->create($request->getClientIp());

        // Check if rate limit is exceeded
        if (false === $limiter->consume(1)->isAccepted()) {

            // Display an error message and redirect to the login page
            $this->addFlash('error', 'Vous devez attendre 1 heure pour refaire une tentative.');
            return $this->redirectToRoute('login');
        }

        // Find the reset password entity based on the provided token
        $resetPassword = $resetPasswordRepository->findOneBy(['token' => sha1($token)]);

        // Check if the reset password entity exists and if it has expired
        if (!$resetPassword || $resetPassword->getExpiredAt() < new \DateTime('now')) {
            if ($resetPassword) {
                // Remove the expired reset password entity
                $entityManager->remove($resetPassword);
                $entityManager->flush();
            }
            // Add an error flash message and redirect to the login page
            $this->addFlash('error', 'Votre demande est expirée, veuillez réinitialiser votre mot de passe.');
            return $this->redirectToRoute('login');
        }
        // Create the password reset form
        $passwordForm = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit faire au minimum 8 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe'
                    ])
                ]
            ])
            ->getForm();

        // Handle the submitted password form
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            // Get the submitted password
            $password = $passwordForm->get('password')->getData();

            // Get the associated user and hash the password
            $user = $resetPassword->getUser();
            $hash = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hash);

            // Remove the reset password entity
            $entityManager->remove($resetPassword);
            $entityManager->flush();

            // Add a success flash message and redirect to the login page
            $this->addFlash('success', 'Votre mot de passe a été modifié');
            return $this->redirectToRoute('login');
        }

        // Render the password reset form view
        return $this->render('security/reset_password_edit.html.twig', [
            'form' => $passwordForm->createView()
        ]);
    }

    #[Route('/reset_password_request', name: 'reset_password_request',  methods: ['GET', 'POST'])]
    public function resetPasswordRequest(RateLimiterFactory $passwordRecoveryLimiter, Request $request, UserRepository $userRepository, ResetPasswordRepository $resetPasswordRepository, EntityManagerInterface $entityManager, MailerService $mailerService): Response
    {
        $limiter = $passwordRecoveryLimiter->create($request->getClientIp());
        if (false === $limiter->consume(1)->isAccepted()) {

            $this->addFlash('error', 'Vous devez attendre 1 heure pour refaire une tentative.');
            return $this->redirectToRoute('login');
        }

        // Create an email form
        $emailForm = $this->createFormBuilder()->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez renseigner votre email'
                ])
            ]
        ])->getForm();

        // Handle the submitted form
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            // Get the submitted email
            $emailValue = $emailForm->get('email')->getData();

            // Find the user by email
            $user = $userRepository->findOneBy(['email' => $emailValue]);
            if ($user) {
                // Remove any existing reset password token for the user
                $oldResetPassword = $resetPasswordRepository->findOneBy(['user' => $user]);
                if ($oldResetPassword) {
                    $entityManager->remove($oldResetPassword);
                    $entityManager->flush();
                }

                // Generate a new reset password token and save it
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);

                // Set the expiration time for the token, which is 2 hours from the current time.
                $resetPassword->setExpiredAt(new \DateTimeImmutable('+2 hours'));

                // Generate a random token value
                $token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(30))), 0, 20);
                $hash = sha1($token);
                $resetPassword->setToken($hash);
                $entityManager->persist($resetPassword);
                $entityManager->flush();

                // Send an email with the reset password token
                $mailerService->sendMail($emailValue, 'parrot@example.fr', 'Demande de réinitialisation de mot de passe.', '@email_templates/reset_password.html.twig', [
                    'username' => $user->getFirstname(),
                    'token' => $token
                ]);
            }

            // Add a success flash message and redirect to the login page
            $this->addFlash('success', 'Un email vous a été envoyé pour réinitialisé votre mot de passe');
            return $this->redirectToRoute('login');
        }

        // Render the password reset request form
        return $this->render('security/reset_password_request.html.twig', [
            'form' => $emailForm->createView(),
        ]);
    }
}
