<?php

namespace App\Controller;

use App\Entity\Parties;
use App\Repository\UserRepository;
use App\Repository\CartesRepository;
use App\Repository\JetonsRepository;
use App\Repository\PartiesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class JouerController extends AbstractController
{

    /**
     * @Route("/jouer", name="jouer")
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

        if ($request->getMethod() == 'POST') {
            $userId = $this->getUser()->getId();
            $j1 = $userRepository->find($userId);
            $j2 = $userRepository->find($request->request->get('joueur2'));

            $partie = new Parties;
            $partie->setJoueur1($j1);
            $partie->setJoueur2($j2);
            $partie->setPartieDate(new \DateTime('now'));

            $partie->setNbManche(1);
            $partie->setPartieDefausse(false);
            $partie->setPartieStatue($userId);
            $partie->setJetonChameaux(0);
            $partie->setPointJ1(0);
            $partie->setPointJ2(0);
            $partie->setJetonsJ1([]); //tableau vide, pas encore de jetons au début d'une partie
            $partie->setJetonsJ2([]);
            $partie->setJetonsVictoireJ1(0);
            $partie->setJetonsVictoireJ2(0);

            $cartes = $cartesRepository->findBy([], ['rang' => 'ASC']);

            $tTerrain = []; //on commence avec 3 chameaux
            for ($i = 0; $i < 3; $i++) {
                $tTerrain[] = array_pop($cartes)->getId();
            }

            shuffle($cartes);
            for ($i = 0; $i < 2; $i++) { //on complète avec deux cartes au hasard
                $tTerrain[] = array_pop($cartes)->getId();
            }
            $partie->setpartieTerrain($tTerrain);

            $tMain = [];
            $tMammouth = [];
            for ($i = 0; $i < 5; $i++) { //on distribue 5 cartes à J1
                $carte = array_pop($cartes);
                if ($carte->getRang() === 7) {
                    $tMammouth[] = $carte->getId();
                } else {
                    $tMain[] = $carte->getId();
                }
            }

            $partie->setMainJ1(($tMain));
            $partie->setChameauxJ1($tMammouth);

            $tMain = [];
            $tMammouth = [];
            for ($i = 0; $i < 5; $i++) { //on distribue 5 cartes à J2
                $carte = array_pop($cartes);
                if ($carte->getRang() === 7) {
                    $tMammouth[] = $carte->getId();
                } else {
                    $tMain[] = $carte->getId();
                }
            }

            $partie->setMainJ2(($tMain));
            $partie->setChameauxJ2($tMammouth);

            $tPioche = [];
            for ($i = 0; $i < count($cartes); $i++) {
                $tPioche[] = array_pop($cartes)->getId();
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

        return $this->render('User/Jouer/creer_partie.html.twig', ['joueurs' => $userRepository->findAll()]);
    }

    /**
     * @Route("/afficher-partie/{partie}", name="afficher_partie")
     */

    public function afficherPartie(Parties $partie)
    {
        return $this->render('User/Jouer/afficher_partie.html.twig', ['partie' => $partie]);
    }

    /**
     * @Route("/afficher-plateau/{partie}", name="afficher_plateau")
     */
    public function afficherPlateau(JetonsRepository $jetonsRepository, CartesRepository $cartesRepository, Parties $partie)
    {
        return $this->render('User/Jouer/afficher_plateau.html.twig',
            ['partie' => $partie,
                'jetons' => $jetonsRepository->findByArrayId(),
                'cartes' => $cartesRepository->findByArrayId(),
            ]);
    }

    /**
     * @Route("/actualise-plateau/{partie}", name="actualise_plateau")
     */
    public function actualisePlateau(Parties $partie)
    {

        return $this->json($partie->getPartieStatue());
    }

    /**
     * @Route("/liste-partie", name="partie_liste")
     */

    public function listePartie()
    {

        $repository = $this->getDoctrine()->getRepository(Parties::class);
        $iduser = $this->getUser()->getId();
        $partie = $repository->findBy(['joueur1' => $iduser]);
        $partiee = $repository->findBy(['joueur2' => $iduser]);

        return $this->render('User/Jouer/liste_partie.html.twig', [
            'parties' => $partie,
            'partiees' => $partiee,
        ]);
    }

    /**
     * @Route("/jouer-action/prendre/{partie}", name="jouer_action_prendre")
     */

    public function jouerActionPrendre(
        EntityManagerInterface $entityManager,
        CartesRepository $cartesRepository,
        Request $request,
        Parties $partie
    )
    {
        $idcarte = $request->request->get('cartes');
        $idmammouth = $request->request->get('chameaux');

        if ($idmammouth !== null) {
            $chameau = $cartesRepository->find($idmammouth[0]);
            //je considére que je suis j1.
            $main_chameaux = $partie->getChameauxJ1();
            $terrain = $partie->getPartieTerrain();
            $pioche = $partie->getPartiePioche();
            for ($i = 0; $i < count($idmammouth); $i++) {
                $main_chameaux[] = $idmammouth[$i]; //on ajoute dans la main de J1
                $index = array_search($idmammouth[$i], $terrain);
                unset($terrain[$index]); // on retire du terrain
                if (count($pioche) > 0) {
                    $idcartep = array_pop($pioche);
                    $cartep = $cartesRepository->find($idcartep);
                    if ($cartep !== null) {
                        $terrain[] = $cartep->getId(); //piocher et mettre sur le terrain
                    }
                }
            }

            // executer
            $partie->setChameauxJ1($main_chameaux);
            $partie->setPartieTerrain($terrain);
            $partie->setPartiePioche($pioche);
            $entityManager->flush();
            return $this->json(['carteterrain' => $cartep->getJson(), 'carteschameaux' => $chameau->getJson()], 200);
        }

        if ($idcarte !== null) {
            $carte = $cartesRepository->find($idcarte[0]);

            if ($carte !== null) {
                //je considére que je suis j1.
                $main = $partie->getMainJ1();
                //vérifier s'il y a 7 cartes dans la main (pourrait se faire en js).
                if (count($main) < 7) {
                    $main[] = $carte->getId(); //on ajoute dans la main de J1
                    $terrain = $partie->getPartieTerrain();
                    $index = array_search($carte->getId(), $terrain);
                    unset($terrain[$index]); // on retire du terrain
                    $pioche = $partie->getPartiePioche();
                    if (count($pioche) > 0) {
                        $idcartep = array_pop($pioche);
                        $cartep = $cartesRepository->find($idcartep);
                        if ($cartep !== null) {
                            $terrain[] = $cartep->getId(); //piocher et mettre sur le terrain
                        }
                    }
                    $partie->setMainJ1($main);
                    $partie->setPartieTerrain($terrain);
                    $partie->setPartiePioche($pioche);
                    $entityManager->flush();
                    return $this->json(['carteterrain' => $cartep->getJson(), 'cartemain' => $carte->getJson()], 200);
                } else {
                    return $this->json('erreur7', 500);
                }
            }
        }
        return $this->json('erreur', 500);
    }


    /**
     * @Route("/jouer-action/troquer/{partie}", name="jouer_action_troquer")
     */
    public function JouerActionTroquer(
        EntityManagerInterface $entityManager,
        CartesRepository $cartesRepository,
        Request $request,
        Parties $partie)
    {
        $main = $partie->getMainJ1();
        $idcarteMain = null;

        if ($request->request->get('main') !== null) {
            $idcarteMain = $request->request->get('main');
        }

        $idcarteTerrain = $request->request->get('terrain');
        $idcarteMainChameau = null;

        if ($request->request->get('chameaux_main') !== null) {
            $idcarteMainChameau = $request->request->get('chameaux_main');
        }

        $nbMain = count($main);

        if ($idcarteMain !== null) {
            $nbCarteMain = count($idcarteMain);
        } else {
            $nbCarteMain = 0;
        }

        $nbCarteTerrain = count($idcarteTerrain);
        $calculMain = $nbMain - $nbCarteMain + $nbCarteTerrain;

        if ($calculMain <= 7) {
            if ($idcarteMain !== null || $idcarteMainChameau !== null) {
                $carteMain = null;
                if ($idcarteMain !== null) {
                    $carteMain = $cartesRepository->find($idcarteMain[0]);
                }
                $carteTerrain = $cartesRepository->find($idcarteTerrain[0]);
                if ($idcarteMainChameau !== null) {
                    $carteMainChameau = $cartesRepository->find($idcarteMainChameau[0]);
                }
                $terrain = $partie->getPartieTerrain();
                if (count($terrain) <= 5) {

                    //je considére que je suis j1.
                    $main = $partie->getMainJ1();
                    $terrain = $partie->getPartieTerrain();
                    $main_chameaux = $partie->getChameauxJ1();
                    if (isset($idcarteMain)) {
                        # code...
                        // Retirer de la main & ID de la carte retirée 1
                        for ($i = 0; $i < count($idcarteMain); $i++) {
                            $index = array_search($idcarteMain[$i], $main);
                            unset($main[$index]);
                        }
                    }
                    // Retirer du terrain & ID de la carte retirée 2
                    for ($i = 0; $i < count($idcarteTerrain); $i++) {
                        $index = array_search($idcarteTerrain[$i], $terrain);
                        unset($terrain[$index]);
                    }
                    // Retirer du chameaux & ID de la carte retirée 3
                    if (isset($idcarteMainChameau)) {
                        for ($i = 0; $i < count($idcarteMainChameau); $i++) {
                            $index = array_search($idcarteMainChameau[$i], $main_chameaux);
                            unset($main_chameaux[$index]);
                        }
                    }
                    if (isset($idcarteMain)) {
                        // Ajoutes les cartes
                        for ($i = 0; $i < count($idcarteMain); $i++) {
                            $terrain[] = $idcarteMain[$i];
                        }
                    }
                    // Ajouter les cartes
                    for ($i = 0; $i < count($idcarteTerrain); $i++) {
                        $main[] = $idcarteTerrain[$i];
                    }
                    // Ajouter les cartes
                    if (isset($idcarteMainChameau)) {
                        for ($i = 0; $i < count($idcarteMainChameau); $i++) {
                            $terrain[] = $idcarteMainChameau[$i];
                        }
                    }
                    // Appliquer les changements
                    $partie->setMainJ1($main);
                    $partie->setPartieTerrain($terrain);
                    $partie->setChameauxJ1($main_chameaux);
                    $entityManager->flush();
                    return $this->json(['main' => $main, 'terrain' => $terrain, 'cartemain' => $carteMain->getJson(), 'carteterrain' => $carteTerrain->getJson()], 200);
                } else {
                    return $this->json('Erreur action troc', 500);
                }
            }
        } else {

            return $this->json('erreur', 500);
        }
    }

    /**
     * @Route("/jouer-action/suivant/{partie}", name="jouer_action_suivant")
     */
    public function jouerActionSuivant(EntityManagerInterface $entityManager,
                                       Parties $partie) {
        $j1 = $partie->getJoueur1()->getId();
        $j2 = $partie->getJoueur2()->getId();
        $status = $partie->getPartieStatue();
        if ($status === $j1) {
            $partie->setPartieStatue($j2);
            $entityManager->flush();
            return $this->json('Joueur-suivant', 200);
        } elseif ($status === $j2) {
            $partie->setPartieStatue($j1);
            $entityManager->flush();
            return $this->json('Joueur-suivant', 200);
        }
        return $this->json($j1, 200);
    }

}