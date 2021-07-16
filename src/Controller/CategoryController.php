<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="categoryIndex")
     */
    public function index(CategoryRepository $repositoryCat): Response
    {
        $categorys = $repositoryCat->findAll();

        return $this->render('category/index.html.twig', [
            'categorys' => $categorys,
        ]);
    }

    /**
     * @Route("/category/test", name="categoryTest")
     */
    public function test(): Response
    {
        $text = "test";

        return $this->render('category/text.html.twig', [
            'text' => $text,
        ]);
    }

}
