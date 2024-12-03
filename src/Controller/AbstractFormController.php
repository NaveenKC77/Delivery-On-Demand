<?php

namespace App\Controller;

use App\Entity\EntityInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use function Cake\Core\toString;

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

            // Check if entity has image or not , if yes, process Upload.

            if (property_exists($entity, 'imagePath')) {
                $imagePath = $this->form->get('imagePath')->getData();

                if ($imagePath) {
                    $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
                    $entity->setImagePath('./images/uploads/' . $newFileName);
                }
            }

            try {
                $this->getService()->add($entity);
                $this->addFlash(static::SUCCESS, $this->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }
        return $this->form;
    }
    public function read()
    {
        return $this->render($this->getTemplateName(), $this->getTemplateData());
    }
    protected function update(EntityInterface $entity, Request $request): FormInterface|RedirectResponse
    {

        $this->form = $this->createForm($this->getFormType(), $entity);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();

            // Check if entity has image or not , if yes, process Upload.

            if (property_exists($entity, 'imagePath')) {
                $entity->setImagePath($this->getData());
                $imagePath = $this->form->get('imagePath')->getData();

                if (null !== $imagePath) {
                    $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
                    $entity->setImagePath('./images/uploads/' . $newFileName);
                }
            }

            try {
                $this->getService()->edit($entity);
                $this->addFlash(static::SUCCESS, $this->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }

        return $this->form;
    }

    protected function delete($id): Response
    {
        try {
            $entity = $this->getService()->getOneById($id);
            $this->getService()->delete($entity);
            $this->addFlash(static::SUCCESS, $this->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        } catch (Exception $e) {
            $this->addFlash(static::ERROR, $e->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        }
    }


    public function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage): Pagerfanta
    {
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage($maxPerPage);
        $pagination->setCurrentPage($currentPage);
        return $pagination;
    }
}
