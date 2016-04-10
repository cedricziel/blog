<?php

namespace CedricZiel\Blog\Service;

use CedricZiel\Blog\Entity\Post;
use GDS\Store;

class PostService
{
    /**
     * @var Store
     */
    private $postStore;

    /**
     * @param Store $postStore
     */
    public function __construct(Store $postStore)
    {
        $this->postStore = $postStore;
    }

    /**
     * @return Post[]
     */
    public function findLatestPublishedPosts()
    {
        return $this->postStore->fetchAll('SELECT * FROM Post WHERE draft = 0 ORDER BY published_at DESC');
    }

    /**
     * @return Post[]
     */
    public function findLatestPosts()
    {
        return $this->postStore
            ->fetchAll('SELECT * FROM Post ORDER BY created_at ASC, __key__ ASC');
    }

    /**
     * @param Post $post
     */
    public function save(Post $post)
    {
        $this->postStore->upsert($post);
    }
}
