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
            $partie->setJetonsVictoireJ1(0);
            $partie->setJetonsVictoireJ2(0);

            $cartes = $cartesRepository->findBy([], ['rang' => 'ASC']);

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
                if ($carte->getRang() === 7){
                    $tMammouth[]=$carte->getId();
                } else {
                    $tMain[] = $carte->getId();
                }
            }

            $partie->setMainJ1(($tMain));
            $partie->setChameauxJ1($tMammouth);

            $tMain=[];
            $tMammouth = [];
            for ($i=0; $i<5; $i++){ //on distribue 5 cartes à J2
                $carte = array_pop($cartes);
                if ($carte->getRang() === 7){
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
     * @Route("/afficher-plateau/{partie}", name="afficher_plateau")
     */
    public function afficherPlateau(JetonsRepository $jetonsRepository, CartesRepository $cartesRepository, Parties $partie) {
        return $this->render('User/Jouer/afficher_plateau.html.twig',
            ['partie' => $partie,
                'jetons' => $jetonsRepository->findByArrayId(),
                'cartes' => $cartesRepository->findByArrayId(),
            ]);
    }
    /**
     * @Route("/actualise-plateau/{partie}", name="actualise_plateau")
     */
    public function actualisePlateau(Parties $partie) {
        switch ($partie->getPartieStatue()) {
            //tester si je suis J1 ou J2 et en fonction adapter les return.
            case '1':
                return $this->json('montour');
            case '2':
                return $this->json('touradversaire');
            case 'T':
                return $this->json('T');
            default:
                return $this->json('E');
        }
    }

    /**
     * @Route("/liste-partie", name="partie_liste")
     */

    public function listePartie() {

        $repository = $this->getDoctrine()->getRepository(Parties::class);
        $iduser = $this->getUser()->getId();
        $partie = $repository->findBy(['joueur1' => $iduser ]) ;
        $partiee = $repository->findBy(['joueur2' => $iduser ]);

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
    ) {
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
        Parties $partie){

        $cterrain = $request->request->get('cterrain');
        $carteterrain = $cartesRepository->find($cterrain[0]);
        $cmain = $request->request->get('cmain');

        if ($cmain !== null && is_array($cmain) && count($cmain)>0) {
            $cartemain = $cartesRepository->find($cmain[0]);
        } else {
            $cartemain = null;
        }

        $cmammouth = $request->request->get('cmammouth');

        if ($cmammouth !== null && is_array($cmammouth) && count($cmammouth)>0){
            $cartemammouth = $cartesRepository->find($cmammouth[0]);
        } else {
            $cartemammouth = null;
        }


        if ($carteterrain !== null) {
            //je considére que je suis j1.
            $main = $partie->getMainJ1();
            $enclos = $partie->getChameauxJ1();
            $terrain = $partie->GetPartieTerrain();

                $compter = count($cterrain); //on compte le nombre de cartes sélectionnées
                for ($i=0; $i < $compter ; $i++){
                    $countmammouth = $cartesRepository->find($cterrain[$i]);

                    if ($countmammouth->getId() > 44){
                        return $this->json('erreurmammouth', 500);
                    } else {

                        $ccterrain= $cartesRepository->find($cterrain[$i]);
                        $main[] = $ccterrain->getId(); //on ajoute celles du terrain dans la main de J1
                        $index = array_search($ccterrain->getId(), $terrain);
                        unset($terrain[$index]); // on retire du terrain

                        if ($cmain !== null && is_array($cmain) && count($cmain)>0){
                            $ccmain = $cartesRepository->find($cmain[$i]);
                            $terrain[]=$ccmain->getId(); //on ajoute celles de la main dans le terrain
                            $indexmain = array_search($ccmain->getId(), $main);
                            unset($main[$indexmain]);
                        } else {

                        } if ($cmammouth !== null && is_array($cmammouth) && count($cmammouth)>0) {
                            $ccmammouth = $cartesRepository->find($cmammouth[$i]);
                            $terrain[]=$ccmammouth->getId(); //on ajoute celles de l'enclos dans le terrain
                            $indexmammouth = array_search($ccmammouth->getId(),$enclos);
                            unset($terrain[$indexmammouth]);

                        } else {
                            $cartemain = null;
                        }

                        $partie->setMainJ1($main);
                        $partie->setPartieTerrain($terrain);
                        $partie->setChameauxJ1($enclos);
                        $entityManager->flush();
                        return $this->json(['cartemain' => $carteterrain->getJson()], 200);
                    }

                }

        }
        return $this->json('erreur', 500);
    }

    /**
     * @Route("/jouer-action/suivant/{partie}", name="jouer_action_suivant")
     */
    public function jouerActionSuivant( EntityManagerInterface $entityManager,
                                        Parties $partie)
    {
        $partie->setPartieStatue('2'); //en considérant que je suis J1 ... a calculer.
        $entityManager->flush();
        return $this->json('Joueur-suivant', 200);
    }
}