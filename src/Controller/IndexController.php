<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */

    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/user", name="user")
     */

    public function user()
    {
        return $this->render('User/index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     */

    public function admin()
    {
        return $this->render('Admin/index.html.twig');
    }
}