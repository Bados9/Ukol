<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        $users = $this->getAllUsers();
        $data = $users->getContent();
        dump(json_decode($data));
        return $this->render('user/homepage.html.twig', [
            'users'=>json_decode($data)
        ]);
    }

    /**
     * @Route("/user/add", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function saveUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $body = $request->getContent();
        $data = json_decode($body, true);

        $form = $this->createForm(RegistrationType::class, $user);
        $form->submit($data);

        $password = $passwordEncoder->encodePassword($user, $user->getPasswordText());
        $user->setPassword($password);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response("User created", 201);
    }

    /**
     * @Route("/user/getall", methods={"GET"})
     */
    public function getAllUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $data = array();
        foreach ($users as $u){
            $array = [
                "name" => $u->getName(),
                "email" => $u->getEmail(),
                "role" => $u->getRole(),
            ];
            array_push($data, $array);
        }
        $response = new Response(json_encode($data),200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}