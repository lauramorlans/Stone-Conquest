<?php

namespace App\Controller;

use App\Entity\Parties;
use App\Repository\UserRepository;
use App\Repository\CartesRepository;
use App\Repository\JetonsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class JouerController extends AbstractController
{

    /**
     * @Route("/Jouer", name="Jouer")
     */
    public function index()
    {
        return $this->render('User/Jouer/index.html.twig', ['controller_name' => 'JouerController',]);
    }

    /**
     * @Route("/creer-partie", name="creer_partie")
     */
    public function creerPartie(UserRepository $userRepository, CartesRepository $cartesRepository, JetonsRepository $jetonsRepository, Request $request)
    {
        if ($request->getMethod() == 'POST'){
            $j1 = $userRepository->find($request->request->get('joueur1'));
            $j2 = $userRepository->find($request->request->get('joueur2'));

            $partie = new Parties;
            $partie->setJoueur1($j1);
            $partie->setJoueur2($j2);
            $partie->setPartieDate(new \DateTime('now'));

            $partie->setNbManche(1);
            $partie->setPartieDefausse(false);
            $partie->setPartieStatue('1');
            $partie->setJetonChameaux(0);
            $partie->setPointJ1(0);
            $partie->setPointJ2(0);
            $partie->setJetonsJ1([]); //tableau vide, pas encore de jetons au début d'une partie
            $partie->setJetonsJ2([]);

            $cartes = $cartesRepository->findBy([], ['rang' => 'DESC']);;

            $tTerrain = []; //on commence avec 3 chameaux
            for ($i=0; $i<3; $i++){
                $tTerrain[] = array_pop($cartes)->getId();
            }

            shuffle($cartes);
            for ($i=0; $i<2; $i++){ //on complète avec deux cartes au hasard
                $tTerrain[] = array_pop($cartes)->getId();
            }
            $partie->setpartieTerrain($tTerrain);

            $tMain=[];
            $tMammouth = [];
            for ($i=0; $i<5; $i++){ //on distribue 5 cartes à J1
                $carte = array_pop($cartes);
                if ($carte->getRang() === 0){
                    $tMammouth[]=$carte->getId();
                } else {
                    $tMain[] = $carte->getId();
                }
            }

            $partie->setMainJ1(($tMain));
            $partie->setChameauxJ1($tMammouth);

            $tMain=[];
            $tMammouth = [];
            for ($i=0; $i<5; $i++){ //on distribue 5 cartes à J1
                $carte = array_pop($cartes);
                if ($carte->getRang() === 0){
                    $tMammouth[]=$carte->getId();
                } else {
                    $tMain[] = $carte->getId();
                }
            }

            $partie->setMainJ2(($tMain));
            $partie->setChameauxJ2($tMammouth);

            $tPioche = [];
            for ($i=0; $i<count($cartes); $i++){
                $tPioche[]= array_pop($cartes)->getId();
            }
            $partie->setPartiePioche($tPioche); //les dernières cartes constituent la pioche

            //construire les jetons sur le terrain
            $jetons = $jetonsRepository->findByTypeRang();
            $partie->setJetonsTerrain($jetons);

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();

            return $this->redirectToRoute('afficher_partie', ['partie' => $partie->getId()]);
        }

        return $this->render('User/Jouer/creer_partie.html.twig', [ 'joueurs' => $userRepository->findAll()]);
    }

    /**
     * @Route("/afficher-partie/{partie}", name="afficher_partie")
     */

    public function afficherPartie(Parties $partie){
        return $this->render('User/Jouer/afficher_partie.html.twig', ['partie' => $partie]);
    }

    /**
     * @Route("/liste-partie", name="partie_liste")
     */

    public function listePartie() {
    }
}