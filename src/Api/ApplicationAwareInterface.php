<?php

namespace CedricZiel\Blog\Api;

use CedricZiel\Blog\BlogApplication;

/**
 * @package CedricZiel\Blog\Api
 */
interface ApplicationAwareInterface
{
    /**
     * @param BlogApplication $application
     * @return void
     */
    public function setApplication(BlogApplication $application);
}
