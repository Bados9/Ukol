<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     * @return RedirectResponse|Response
     */
    public function register()
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render(
            'registration/registration.html.twig',
            array('form' => $form->createView())
        );
    }
}