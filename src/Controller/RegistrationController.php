<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/creer-nouveau-compte', name: 'app_register')]
    #[IsGranted('ROLE_ADMIN')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($request->request->get('civilite') === null){
                    
                return $this->render('adminstration/registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'civiliteError' => true,
                ]);

            }else{
                $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $civilite=$request->request->get('civilite');
                $user->setCivilite($civilite);

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('support@gattino.fr', 'Mailer Admin'))
                        ->to($user->getEmail())
                        ->subject('Confirmation de mail')
                        ->htmlTemplate('administration/registration/confirmation_email.html.twig')
                );
                

                $this->addFlash('success', 'Pour vérifier le mail de ce nouveau compte, veuillez cliquer sur le lien envoyé au courriel renseigné.');
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('administration/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'civiliteError' => false,
        ]);
    }

    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/verification-email', name: 'app_verify_email')]
    #[IsGranted('ROLE_ADMIN')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre mail est bien verifié.');

        return $this->redirectToRoute('app_login');
    }
}
