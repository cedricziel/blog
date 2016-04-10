<?php

namespace CedricZiel\Blog;

use Silex\Application;
use Silex\Application\MonologTrait;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;
use Symfony\Component\Form\FormFactory;

/**
 * @property FormFactory $this['form.factory']
 *
 * @package CedricZiel\Blog
 */
class BlogApplication extends Application
{
    use MonologTrait;
    use TwigTrait;
    use UrlGeneratorTrait;
}
