<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account", name="account_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, 
                            EntityManagerInterface $entityManagerInterface, 
                            UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $token = $request->request->get('_csrf_token');

            if ($this->isCsrfTokenValid("yusufheri64", $token)) {
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();

                $this->addFlash(
                    "success",
                    '<h4 class="alert-heading">Félicitations !</h4>
                    <p class="mb-0">Votre compte a été créé avec succès, <a href="/login">connectez-vous maintenant</a> :)</p>'
                );

                return $this->redirectToRoute("app_login");
            } else {
                $this->addFlash(
                    "danger",
                    '<h4 class="alert-heading">Avertissement !</h4>
                    <p class="mb-0">Le jeton envoyé n est pas  valide, prière de contacter l administrateur</p>'
                );
            }
        }

        return $this->render('dashboard/account/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
