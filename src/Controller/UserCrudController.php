<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

// Define the route '/dashboard/utilisateur' accessible only to 'ROLE_ADMIN' users.
#[Route('/dashboard/utilisateur')]
#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Paginate the list of user records.
        $users = $paginator->paginate(

            // Fetch all user records from the database with names sorted alphabetically.
            $userRepository->findBy([], ['lastname' => 'ASC']),

            // Get the current page number from the request, default to page 1.
            $request->query->getInt('page',1),
            // Display 6 user records per page.
            6
        );

        //Render template, passing the paginated user data.
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/nouveau', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Create a new User object.
        $user = new User();

        // Create a form using the UserType and add a password field to it.
        $form = $this->createForm(UserType::class, $user)
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ]);

        // Handle the form submission.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash the user's password.
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Persist the user object to the database.
            $entityManager->persist($user);
            $entityManager->flush();

            // Add a success flash message and redirect to the user index page.
            $this->addFlash('success', 'Le salarié est créé ! ');
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);

            // If the form was submitted but is not valid, add an error flash message
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Attention ! Le salarié n\'a pas été enregistré');
        }

        // Render the new user creation form.
        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Show details of a user.
    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Edit a user's details.
    #[Route('/{id}/modifier', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Create a form for user editing.
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the edited user details to the database.
            $entityManager->flush();

            // Add a success flash message and redirect to the user index page.
            $this->addFlash('success', 'Le salarié a été modifié ! ');
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);

            // If the form was submitted but is not valid, add an error flash message
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Attention ! Le salarié n\'a pas été modifié !');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Delete a user.
    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Check the CSRF token for security.
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Le salarié a été supprimé ! ');
        }
        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
