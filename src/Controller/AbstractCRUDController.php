<?php

namespace App\Controller;

use App\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Summary of AbstractFormController
 * Abstract form controller for All Entities Apart from UserTypes , uses FormControllerTraits
 * provides Create , Update and Delete Abstract Functions
 */
abstract class AbstractCRUDController extends AbstractController
{
    use AppControllerTrait;

    /**
     * Summary of getUploadDir
     * @return string
     *                returns upload Directory for files uploaded via form
     */
    abstract protected function getUploadDir(): string;

    /**
     * Hooks for subclasses to perform additional actions post-CRUD.
     */
    protected function postCreateHook(object $entity): void
    {
    }
    protected function postUpdateHook(object $entity): void
    {
    }
    protected function postDeleteHook(object $entity): void
    {
    }

    /**
     * Summary of create
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     *                                                                                                                                                  creates new object of provided entity type if form submitted , else returns form for rendering twig template
     */
    protected function create(Request $request): FormInterface|RedirectResponse|Response|null
    {
        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();

            try {

                $this->getService()->add($entity);

                // for creating events, eg: returns category for Category Class from where event can be triggered using entity
                $this->setData($entity);

                $this->addFlash(static::SUCCESS, $this->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }

        return $this->form;
    }

    /**
     * Summary of update
     * @param \App\Entity\EntityInterface $entity
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
     *                                                                                                  nt\HttpFoundation\Response|null
     *                                                                                                  creates new object of provided entity type if form submitted , else returns form for rendering twig template
     */
    protected function update(EntityInterface $entity, Request $request): FormInterface|RedirectResponse
    {
        $this->form = $this->createForm($this->getFormType(), $entity);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();

            try {
                $this->getService()->edit($entity);
                // for creating events, eg: returns category for Category Class from where event can be triggered using entity
                $this->setData($entity);

                $this->addFlash(static::SUCCESS, $this->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }

        return $this->form;
    }

    /**
     * Summary of delete
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\Response
     *                                                    deletes the object and redirects to the provided redireaction route
     */
    protected function delete($id): Response
    {
        try {
            $entity = $this->getService()->getOneById($id);
            $this->getService()->delete($entity);
            $this->addFlash(static::SUCCESS, $this->getMessage());

            return $this->redirectToRoute($this->getRedirectRoute());
        } catch (\Exception $e) {
            $this->addFlash(static::ERROR, $e->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        }
    }
}
