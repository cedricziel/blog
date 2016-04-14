<?php

namespace CedricZiel\Blog\Controller;

use CedricZiel\Blog\Api\ApplicationAwareInterface;
use CedricZiel\Blog\Traits\ApplicationAwareTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package CedricZiel\Blog\Controller
 */
abstract class AbstractController implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->application->render($view, $parameters, $response);
    }
}
