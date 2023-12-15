<?php

namespace App\Controller;

use Doctrine\DBAL\Driver\AbstractDB2Driver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArtistaController extends AbstractController
{
    public function index()
    {
        return new Response('Hello!');
    }
}