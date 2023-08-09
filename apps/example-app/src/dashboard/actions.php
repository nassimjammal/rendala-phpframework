<?php

use Symfony\Component\HttpFoundation\Response;

class dashboardController
{
    public function index(string $name): Response
    {
        return new Response('Hello ' . $name);
    }
}