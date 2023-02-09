<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SendMail\SendMailService;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/dmlsdfpso165sdfsedSDeEsesdfDCjFSxcwlkxiof165/se-connecter', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_admin');
        }
        // dd('hello');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('administration/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/se-déconnecter', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $doctrine, SendMailService $sendMailService): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());
            
            //vérifier si l'utilisateur existe
            if($user){
                // générer un token
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $doctrine->persist($user);
                $doctrine->flush();

                // générer un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // créer les données du mail
                $context = compact('url', 'user'); // équivalent au ['url' => $url, 'user' => $user]

                // envoi du mail
                $sendMailService->send(
                    'no-reply@gattino.fr',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');

            }else{
                $this->addFlash('danger', "Le mail entré n'est pas reconnu");
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('administration/security/reset_password_request.html.twig',[
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $doctrine,
        UserPasswordHasherInterface $passHasher): Response
    {
       // vérifier si le token existe dans la bd
       $user = $userRepository->findOneByResetToken($token); 
       if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // effacer le token
                $user->setResetToken('');
                $user->setPassword(
                    $passHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $doctrine->persist($user);
                $doctrine->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('administration/security/reset_password.html.twig', [
                'resetPassForm' => $form->createView(),
            ]);

       }
       $this->addFlash('danger', 'Jeton invalide');
       return $this->redirectToRoute('app_login');
    }
}
