<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return $this->render(view:'home/index.html.twig');
    }


    #[Route('/custom/{name?}', name: 'custom')]// {name?}-> param de route opcional.
    public function custom(Request $request)
    {   
        $name = $request->get(key:'name');

        return $this -> render('home/custom.html.twig', ['name' => $name]);
    }
}
  