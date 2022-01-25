<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Form\PostType;


#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository){
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }
    
    #[Route('/create', name: 'create')]
    public function create(ManagerRegistry $doctrine,Request $request){
        // create a new post with a title
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirect($this->generateUrl(route:'post.index'));
        }
        // return a response
        return $this->render('post/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/show/{id}', name: 'show')]
    public function show($id,PostRepository $postRepository){
        $post = $postRepository->find($id);
        dump($post);
        return $this->render('post/show.html.twig',[
            'post' => $post
        ]);
    }
    
    
    #[Route('/delete/{id}', name: 'delete')]
    public function remove(ManagerRegistry $doctrine,int $id){
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();
        $this->addFlash(type:'success', message:'Post was deleted');
        return $this->redirect($this->generateUrl(route:'post.index'));
    }
}
