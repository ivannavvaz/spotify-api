<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CapituloController extends AbstractController
{
    public function index()
    {
        return new Response('Hello!');
    }
}