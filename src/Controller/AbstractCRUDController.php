<?php

namespace App\Controller;

use App\Entity\EntityInterface;
use App\Services\EntityServicesInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCRUDController extends AbstractReadController
{
    private ?FormInterface $form = null;
    private mixed $data = null;

    private string $redirectRoute = "";

    protected function getData(): mixed
    {
        return $this->data;
    }

    protected function setData(mixed $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get upload directory to upload files
     * to be implemented by child class
     * @return string
     */
    abstract protected function getUploadDir(): string;

    /**
     * Get form type for the class , to be implemented by child class
     * @return string
    */
    abstract protected function getFormType(): string;

    abstract protected function getEntityName(): string;

    protected function getRedirectRoute(): string
    {
        return $this->redirectRoute;
    }

    protected function setRedirectRoute(string $redirectRoute): static
    {
        $this->redirectRoute = $redirectRoute;
        return $this;
    }

    /**
            * Get the basic entity service class
             * @return EntityServicesInterface
            */
    abstract protected function getEntityServiceType(): EntityServicesInterface;


    abstract protected function postUpdateHook(EntityInterface $entity): void;
    abstract protected function postCreateHook(EntityInterface $entity): void;

    abstract protected function postDeleteHook(EntityInterface $entity): void;

    public function create(Request $request): FormInterface|RedirectResponse|null
    {
        // create form and handle request
        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();

            try {
                // persist data to db
                $this->getEntityServiceType()->save($entity);

                // post create actions, raise event , create logs...
                $this->postCreateHook($entity);

                // add Flash for confirmation message
                $this->addFlash(static::SUCCESS, $this->getEntityName() . " created.");

                // redirect user
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (Exception $e) {
                throw new Exception("Sorry, could not be added");
            }
        }

        return $this->form;
    }

    public function update(int $id, Request $request): FormInterface|RedirectResponse|null
    {
        // get the item from db
        $entity = $this->getEntityServiceType()->getOneById($id);

        // create form , and handle it
        $this->form = $this->createForm($this->getFormType(), $entity);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            // get form dtata and assign it to entity
            $entity = $this->form->getData();

            try {
                // persist to db
                $this->getEntityServiceType()->save($entity);

                // post update , raise events for logging .....
                $this->postUpdateHook($entity);

                // add flash for ui confirmation
                $this->addFlash(static::SUCCESS, $this->getEntityName() . " updated. ");

                // redirect the user
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
              
            }
        }

        return $this->form;

    }

    public function delete(int $id): RedirectResponse|null
    {
        // get the item from db
        $entity = $this->getEntityServiceType()->getOneById($id);

        try {
            // delete from db
            $this->getEntityServiceType()->delete($entity);

            // post delete hook , raise event
            $this->postDeleteHook($entity);

            // add Flash for Ui confirmation
            $this->addFlash(static::SUCCESS, $this->getEntityName() . " deleted.");

            // redirect user
            return $this->redirectToRoute($this->getRedirectRoute());
        } catch (Exception $e) {
            throw new Exception("Sorry , Deletion Failed");
        }

    }
}
