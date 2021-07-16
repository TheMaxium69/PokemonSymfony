<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Pokemon;
use App\Form\CategoryType;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use function PHPUnit\Framework\throwException;

class PokemonController extends AbstractController
{
    /**
     * @Route("/pokemon", name="pokemonIndex")
     */
    public function index(PokemonRepository $repository): Response
    {
        $AllPokemon = $repository->findAll();

        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $AllPokemon
        ]);
    }


    /**
     * @Route("/pokemon/show/{id}", name="pokemonShow")
     */
    public function show(Pokemon $pokemon): Response
    {

        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon
        ]);
    }

    /**
     * @Route("/pokemon/create/", name="pokemonCreate")
     * @Route ("/pokemon/edit/{id}", name="pokemonEdit")
     */
    public function new(Pokemon $pokemon = null, Request $laRequete, EntityManagerInterface $manager) : Response
    {
        $modeCreate = false;

        if (!$pokemon) {
            $pokemon = new Pokemon();
            $modeCreate = true;
        }

        $form = $this->createForm(PokemonType::class, $pokemon);

        $form->handleRequest($laRequete);
        if ($form->isSubmitted() && $form->isValid())
        {
            $imgSend = $form->get('img')->getData();

            if ($imgSend){

                try {
                    $newNameImage = uniqid() . "." . $imgSend->guessExtension();
                    $imgSend->move($this->getParameter('images_pokemon'), $newNameImage);
                    if ($modeCreate){
                        $pokemon->setImg($newNameImage);
                    }
                } catch (FileException $e) {
                    throw $e;
                    return $this->redirectToRoute('pokemonIndex');
                }
            }

            $manager->persist($pokemon);
            $manager->flush();

            return $this->redirectToRoute('pokemonShow', [
                "id" => $pokemon->getId()
            ]);
        }else {
            return $this->render('pokemon/form.html.twig', [
                'formPokemon' => $form->createView(),
                'isCreate' => $modeCreate
            ]);
        }

    }

    /**
     * @Route("/pokemon/del/{id}", name="pokemonDel")
     */
    public function del(Pokemon $pokemon, EntityManagerInterface $manager){

        $manager->remove($pokemon);
        $manager->flush();


        return $this->redirectToRoute('pokemonIndex');
    }

    /**
     * @Route("/pokemon/category/new", name="categoryCreate")
     */
    public function create(Request $requested, EntityManagerInterface $manager) : Response
    {

        $category = new Category();

        $formCat = $this->createForm(CategoryType::class, $category);

        $formCat->handleRequest($requested);
        if ($formCat->isSubmitted() && $formCat->isValid()) {

            $manager->persist($category);
            $manager->flush();

        } else {
            return $this->render('category/form.html.twig', [
                'formCategory' => $formCat->createView(),
            ]);
        }
    }

}
