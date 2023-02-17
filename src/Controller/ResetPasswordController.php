<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                $reset_password = new ResetPassword;
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTime());

                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();


                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()." Vous demandez à rénitialiser votre mot de passe sur McKing.</br></br>";
                $content .= " Merci de cliquer sur le lien suivant pour <a href='http://5.196.194.205:8000".$url."'>mettre à jour votre mot de passe.</a>";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Rénitialiser votre mot de passe', 'Mot de passe oublié', $content);

                $this->addFlash('notice', 'Un email viens de vous etre envoyé.');
            } else {
                $this->addFlash('notice', "L'adresse email est inconnue.");
            }
        }



        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new DateTime();

        if ($now > $reset_password->getCreatedAt()->modify('+1 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe à expiré.');
            $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();

            $password = $encoder->encodePassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe à bien été mis à jour.');

            return $this->redirectToRoute('login');
            
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

        dd($reset_password);
    }

}
