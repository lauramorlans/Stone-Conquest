<?php

// src/Controller/LuckyController.php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class LuckyController extends Controller {

    /**
     * @Route("/lucky/number", name="app_lucky_number")
     */
    public function number(){
        $number = mt_rand(0,100);
        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }

    /**
     * @Route("/lucky/number1", name="app_lucky_number1")
     */

    public function number1(){
        $number = mt_rand(15,75);
        return $this->render('lucky/number1.html.twig', array(
            'number1' => $number,
        ));
    }

    /**
     * @Route("/lucky/number2", name="app_lucky_number2")
     */

    public function number2(){
        $number = mt_rand(25,50);
        return $this->render('lucky/number2.html.twig', array(
            'number2' => $number,
        ));
    }

    /**
     * @Route("/lucky/info", name="app_lucky_info")
     */

    public function info(){
        return $this->render('lucky/info.html.twig');
    }
}

?>