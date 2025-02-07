<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ResetPasswordForm;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private LoginFormAuthenticator $loginFormAuthenticator;
    private EmailVerifier $emailVerifier;
    public function __construct(EmailVerifier $emailVerifier,LoginFormAuthenticator $loginFormAuthenticator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $firstName = $form->get('firstname')->getData();
            $lastName = $form->get('lastname')->getData();
            $email = $form->get('email')->getData();
            $user->setFirstname($firstName);
            $user->setLastname($lastName); 
            $user->setEmail($email);
            $user->setPassword($plainPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            // Vérification Email (générée automatiquement)
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('aitoualimarouane@gmail.com', 'koulMaghreb'))
                    ->to((string) $user->getEmail())
                    ->subject('Confirmez votre adresse e-mail pour finaliser votre inscription')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    
    #[Route('/reset-password', name: 'mot_de_passe_oublie')]
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        // Créer le formulaire de mise à jour du mot de passe
        $form = $this->createForm(ResetPasswordForm::class);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $email = $form->get('email')->getData();
            $oldPassword = $form->get('old_password')->getData();
            $newPassword = $form->get('new_password')->getData();

            // Vérifier si l'utilisateur existe avec cet email
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            if (!$user) {
                // Si l'utilisateur n'existe pas, afficher un message d'erreur
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('mot_de_passe_oublie');
            }

            // Vérifier si le mot de passe actuel est valide
            if (!$passwordEncoder->isPasswordValid($user, $oldPassword)) {
                // Si le mot de passe actuel est incorrect, afficher un message d'erreur
                $this->addFlash('error', 'L\'ancien mot de passe est incorrect.');
                return $this->redirectToRoute('mot_de_passe_oublie');
            }

            // Hacher le nouveau mot de passe
            $user->setPassword($newPassword);

            // Sauvegarder les modifications dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

            // Rediriger pour éviter la soumission multiple du formulaire
            return $this->redirectToRoute('app_login');
        }

        // Rendre la vue avec le formulaire
        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
