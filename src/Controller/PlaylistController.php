<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends AbstractController
{
    public function index()
    {
        return new Response('Hello!');
    }
}