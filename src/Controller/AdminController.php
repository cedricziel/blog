<?php

namespace CedricZiel\Blog\Controller;

use CedricZiel\Blog\Api\ApplicationAwareInterface;
use CedricZiel\Blog\Entity\Post;
use CedricZiel\Blog\Service\PostService;
use CedricZiel\Blog\Traits\ApplicationAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

/**
 * @property FormFactory $application['form.factory']
 *
 * @package CedricZiel\Blog\Controller
 */
class AdminController implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var Twig_Environment
     */
    private $view;

    /**
     * @param Twig_Environment $twig
     * @param PostService $postService
     */
    public function __construct(Twig_Environment $twig, PostService $postService)
    {
        $this->postService = $postService;
        $this->view = $twig;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $post = new Post;
        $postForm = $this->createPostForm($post);

        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();

            $this->postService->save($post);
        }

        $posts = $this->postService->findLatestPosts();

        return new Response(
            $this->view->render(
                'Admin/index.html.twig',
                [
                    'posts' => $posts,
                    'postForm' => $postForm->createView(),
                ]
            )
        );
    }

    /**
     * @param Post $post
     * @return Form
     */
    protected function createPostForm($post)
    {
        /** @var FormFactory $formFactory */
        $formFactory = $this->application['form.factory'];
        /** @var Form $postForm */
        $postForm = $formFactory
            ->createBuilder(FormType::class, $post)
            ->add('title')
            ->add('draft', CheckboxType::class)
            ->add('created_at', DateTimeType::class)
            ->add('updated_at', DateTimeType::class)
            ->add('published_at', DateTimeType::class)
            ->add('body', TextareaType::class)
            ->add('submit', SubmitType::class)
            ->setAction($this->application->path('admin.index'))
            ->getForm();

        return $postForm;
    }
}
