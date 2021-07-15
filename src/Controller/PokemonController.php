<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

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
     * @Route ("/pokemon/{id}/edit", name="pokemonEdit")
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
        if ($form->isSubmitted())
        {
            $imgSend = $form->get('img')->getData();
            $newNameImage = uniqid().".".$imgSend->guessExtension();
            $imgSend->move($this->getParameter('images_pokemon'), $newNameImage);
            $pokemon->setImg($newNameImage);

            $manager->persist($pokemon);
            $manager->flush();
            dump($pokemon);

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
     * @Route("/pokemon/{id}/del", name="pokemonDel")
     */
    public function del(Pokemon $pokemon, EntityManagerInterface $manager){

        $manager->remove($pokemon);
        $manager->flush();


        return $this->redirectToRoute('pokemonIndex');
    }

}
