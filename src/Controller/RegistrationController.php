<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Operator\SoundExpression;
use App\Repository\NewsletterRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/unsubscribe', name: 'app_unsubscribe')]
    public function unsubscribeFromNewsletter(Request $request, UserRepository $userRepository, NewsletterRepository $newsletterRepository, EntityManagerInterface $entityManager, TokenService $tokenService): Response
    {
        try {
            $email = $request->query->get('email');
            $token = $request->query->get('token');

            if (!$email || !$token) {
                throw new \RuntimeException();
            }

            $validToken = $tokenService->generateUnsubscribeToken($email);

            if (!hash_equals($validToken, $token)) {
                throw new \RuntimeException();
            }

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                throw new \RuntimeException();
            }

            $newsletter = $newsletterRepository->findOneBy(['email' => $user->getUserIdentifier()]);

            if (!$newsletter) {
                throw new \RuntimeException();
            }

            $user->setHasNewsletter(false);
            $entityManager->persist($user);
            $entityManager->remove($newsletter);
            $entityManager->flush();

            $this->addFlash('success','Erfolgreich vom Newsletter abgemeldet.');

        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Dieser Abmelde-Link ist nicht mehr gültig.');
        }

        return $this->redirectToRoute('app_index');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, NewsletterRepository $newsletterRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setHasSchool(true);

            // Phonetik generieren und speichern
            $user->setFirstnameSoundEx(SoundExpression::generate($user->getFirstname()));
            $user->setLastnameSoundEx(SoundExpression::generate($user->getLastname()));

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Standard-Optionen aktivieren
            $user->setIsContactable(true);
            $user->setHasPinnboard(true);
            $user->setIsEventsVisible(true);

            // Prüfen, ob früher schon der Newsletter abonniert wurde
            if($newsletter = $newsletterRepository->findOneBy(['email' => $user->getEmail()])) {
                $user->setHasNewsletter(true);
                $newsletter->setUser($user);
                $newsletter->addSchool($user->getSchool());
                $entityManager->persist($newsletter);
            } else {
                $user->setHasNewsletter(false);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('service@alumni-portal.org', 'Alumni-Portal'))
                    ->to((string) $user->getEmail())
                    ->subject('Bitte bestätige deine E-Mail-Adresse')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context(['user' => $user])
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register_complete');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/register/confirm', name: 'app_confirm')]
    public function resendConfirmationEmail(Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('service@alumni-portal.org', 'Alumni-Portal'))
                ->to((string) $user->getEmail())
                ->subject('Bitte bestätige deine Email-Adresse')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context(['user' => $user])
        );

        $this->addFlash('success','E-Mail wurde erfolgreich versendet.');
        return $this->redirectToRoute('profile_index');
    }

    #[Route('/register/complete', name: 'app_register_complete')]
    public function registrationComplete(): Response
    {
        return $this->render('registration/register_complete.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('profile_index');
    }
}
