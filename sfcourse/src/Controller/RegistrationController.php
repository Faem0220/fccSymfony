<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    #@param UserPasswordEncoderInterface $passwordEncoder
    public function register(ManagerRegistry $doctrine,
                            Request $request,
                            UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createFormBuilder()
        ->add(child:'username')
        ->add('password',RepeatedType::class,[
            'type'=> PasswordType::class,
            'required'=> true,
            'first_options'=> ['label'=>'Password'],
            'second_options'=>['label'=>'Confirm Password']
        ])
        ->add('Register',SubmitType::class,[
            'attr'=>[
                'class'=> 'btn btn-success float-right'
            ]
        ])
        ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user,$data['password'])
            );
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirect($this->generateUrl(route:'app_login'));
        }
        return $this->render('registration/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
