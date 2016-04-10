<?php

namespace CedricZiel\Blog\Traits;

use Twig_Environment;

/**
 * Adds a setter for a twig instance.
 *
 * @package CedricZiel\Blog\Traits
 */
trait TwigAwareTrait
{
    /**
     * @var Twig_Environment
     */
    protected $view;

    /**
     * @param Twig_Environment $twig
     */
    public function setTwig(Twig_Environment $twig)
    {
        $this->view = $twig;
    }
}
