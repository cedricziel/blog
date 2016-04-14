<?php

namespace CedricZiel\Blog\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package CedricZiel\Blog\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {

        return new Response(
            $this->render('Admin/index.html.twig')
        );
    }

    /**
     * Return information about the environment
     *
     * @param Request $request
     *
     * @return Response
     */
    public function envAction(Request $request)
    {

        return new Response(print_r($_SERVER, true));
    }
}
