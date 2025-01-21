<?php

namespace App\Controller;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;

/**
 * Summary of FormControllerTrait
 * Basic form controller properties and methods including Read and pagination setup
 */
trait FormControllerTrait
{
    protected const SUCCESS = 'success';
    protected const ERROR = 'error';
    protected string $templateName;
    protected string $redirectRoute;
    protected string $message;
    protected array $templateData = [];
    protected mixed $data = null;

    protected ?FormInterface $form;

    abstract protected function getService();

    abstract protected function getFormType(): string;

    public function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage): Pagerfanta
    {
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage($maxPerPage);
        $pagination->setCurrentPage($currentPage);

        return $pagination;
    }

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
