<?php

use Symfony\Component\HttpFoundation\Response;

class testController
{
    public function index(string $name): Response
    {
        return new Response('Bye ' . $name);
    }
}