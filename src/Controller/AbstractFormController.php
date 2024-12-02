<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormController extends AbstractController
{

    protected const SUCCESS = 'success';
    protected const ERROR = 'error';

    protected string $templateName;

    protected string $redirectRoute;
    protected string $message;
    protected array $templateData = [];


    protected mixed $data = null;
    protected ?FormInterface $form;


    abstract protected function getFormType(): string;
    abstract protected function getService();
    abstract protected function getUploadDir(): string;

    public function __construct() {}

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

    protected function create(Request $request): FormInterface|RedirectResponse|Response|null
    {

        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);


        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();
            try {
                $this->getService()->add($entity);
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (Exception $e) {
                return new Response($e->getMessage());
            }
        }
        return $this->form;
    }
    public function read()
    {
        return $this->render($this->getTemplateName(), $this->getTemplateData());
    }
    protected function edit($id, Request $request): FormInterface|RedirectResponse|null
    {
        $entity = $this->getService()->getOneById($id);
        $this->form = $this->createForm($this->getFormType(), $entity);
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->getService()->edit();

            return $this->redirectToRoute($this->getRedirectRoute());
        }

        return $this->form;
    }
    protected function delete($id): Response
    {
        try {
            $entity = $this->getService()->getOneById($id);
            $this->getService()->delete($entity);
            return $this->redirectToRoute($this->getRedirectRoute());
        } catch (Exception $e) {
            return new Response($e->getMessage());
        }
    }
}
