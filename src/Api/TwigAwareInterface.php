<?php

namespace CedricZiel\Blog\Api;

use Twig_Environment;

/**
 * Marks classes that know twig
 *
 * @package CedricZiel\Blog\Api
 */
interface TwigAwareInterface
{
    /**
     * @param Twig_Environment $twig
     * @return void
     */
    public function setTwig(Twig_Environment $twig);
}
