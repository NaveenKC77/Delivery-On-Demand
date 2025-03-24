<?php

namespace App\Controller;

use App\Services\ServicesInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;

/**
 * Summary of App Controller Trait
 * Abstraction of basic controller functionalities for app to be implemented by all controllers
 */
trait AppControllerTrait
{
    protected const SUCCESS = 'success';
    protected const ERROR = 'error';
    /**
     * Summary of templateName
     * @var string
     *             twig template for rendering view
     */
    protected string $templateName;
    /**
     * Summary of redirectRoute
     * @var string
     *             redirecting route when CUD operation is done
     */

    protected string $redirectRoute;

    /**
     * Summary of message
     * @var string
     *             used to store success message for CUD operations
     */
    protected string $message;

    /**
     * Summary of templateData
     * @var array
     *            stores data to be passed to twig when rendering the view
     */
    protected array $templateData = [];

    /**
     * Summary of data
     * @var mixed
     *            pass any extra data required to the CUD operations
     */
    protected mixed $data = null;

    /**
     * Summary of form
     * @var
     * returns FormType
     */
    protected ?FormInterface $form;

    /**
     * Summary of getService
     * @return void
     *              store Service Class
     */
    abstract protected function getService();


    /**
     * Summary of getFormType
     * @return string
     *                return Form Type for each entity type
     */
    abstract protected function getFormType(): string;

    /**
     * Summary of getPagination
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int $currentPage
     * @param int $maxPerPage
     * @return \Pagerfanta\Pagerfanta
     *                                Utilizes PagerFanta to provide pagination
     */
    protected function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage): Pagerfanta
    {
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage($maxPerPage);
        $pagination->setCurrentPage($currentPage);

        return $pagination;
    }

    /**
     * Summary of read
     * simple twig rendering function
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function read()
    {
        return $this->render($this->getTemplateName(), $this->getTemplateData());
    }

    protected function setTemplateName($templateName): static
    {
        $this->templateName = $templateName;

        return $this;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    protected function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;

        return $this;
    }

    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }

    protected function setMessage($message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    protected function setTemplateData($templateData): static
    {
        $this->templateData = $templateData;

        return $this;
    }

    public function getTemplateData(): array
    {
        return $this->templateData;
    }

    protected function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
