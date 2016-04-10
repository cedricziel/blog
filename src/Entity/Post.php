<?php

namespace CedricZiel\Blog\Entity;

use GDS\Entity;

/**
 * @property \DateTime deleted_at
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property bool draft
 * @package CedricZiel\Blog\Entity
 */
class Post extends Entity
{
    public function __construct()
    {
        $this->setKind('Post');

        $this->draft = true;
        $this->created_at = new \DateTime();
        $this->deleted_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }
}
