<?php

namespace CedricZiel\Blog\Controller;

use CedricZiel\Blog\Api\ApplicationAwareInterface;
use CedricZiel\Blog\Api\TwigAwareInterface;
use CedricZiel\Blog\Traits\ApplicationAwareTrait;
use CedricZiel\Blog\Traits\TwigAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package CedricZiel\Blog\Controller
 */
class AdminController implements ApplicationAwareInterface, TwigAwareInterface
{
    use ApplicationAwareTrait;
    use TwigAwareTrait;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {

        return new Response(
            $this->view->render('Admin/index.html.twig')
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
