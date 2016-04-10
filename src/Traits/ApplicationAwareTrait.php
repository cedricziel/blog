<?php

namespace CedricZiel\Blog\Traits;

use CedricZiel\Blog\BlogApplication;

/**
 * Adds a setter for the application container.
 *
 * @package CedricZiel\Blog\Traits
 */
trait ApplicationAwareTrait
{
    /**
     * @var BlogApplication
     */
    protected $application;

    /**
     * @param BlogApplication $application
     */
    public function setApplication(BlogApplication $application)
    {
        $this->application = $application;
    }
}
