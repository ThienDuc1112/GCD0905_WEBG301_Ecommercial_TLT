<?php

namespace App\Controller;

use App\Form\EditUserType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;

/**
 * @Route("/api")
 */

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/about", name="about_us")
     */
    public function about()
    {
        return $this->render('front/about.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(UserPasswordEncoderInterface $password_encoder, Request $request, EntityManagerInterface $entityManager)
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $password_encoder->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->loginUserAutomatically($user, $password);

            return $this->redirectToRoute('main_page');
        }
        return $this->render('front/register.html.twig', ['form' => $form->createView()]);
    }

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main', // security.yaml
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @Route("/profile/{id}", name="profile", methods={"GET"})
     */
    public function Profile(): Response
    {
        return $this->render('front/profile.html.twig');
    }

    /**
     * @Route("/profile/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function EditProfile(Request $request, UserRepository $userRepository, User $user): Response
    {

//        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['avatar']->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.jpg';
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    print($e);


                }
//            $manager = $this->getDoctrine()->getManager();
//            $manager->persist($user);
//               $manager->flush();
                $user->setAvatar($fileName);
            }
            $userRepository->add($user, true);

            $this->addFlash('messenger', 'Edit your profile successful');
            return $this->redirectToRoute('main_page');

        }
        return $this->renderForm('front/edit_profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function generateUniqueFileName(): string
    {
        return md5(uniqid());
    }

}


