<?php

namespace App\Controller;

use App\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFormController extends AbstractController
{
    use FormControllerTrait;

    abstract protected function getUploadDir(): string;

    protected function create(Request $request): FormInterface|RedirectResponse|Response|null
    {
        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();

            // // Check if entity has image or not , if yes, process Upload.

            // if (property_exists($entity, 'imagePath')) {
            //     $imagePath = $this->form->get('imagePath')->getData();

            //     if ($imagePath) {
            //         $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
            //         $entity->setImagePath('./images/uploads/' . $newFileName);
            //     }
            // }

            try {
                $this->getService()->add($entity);
                $this->addFlash(static::SUCCESS, $this->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }

        return $this->form;
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
            } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            $this->addFlash(static::ERROR, $e->getMessage());

            return $this->redirectToRoute($this->getRedirectRoute());
        }
    }
}
