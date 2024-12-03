<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;


abstract class UserAbstractController extends AbstractController
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

    abstract protected function getRoles(): array;

    abstract protected function getFormType(): string;



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


    public function read()
    {
        return $this->render($this->getTemplateName(), $this->getTemplateData());
    }

    // public function create($request, $user)
    // {
    //     $this->form = $this->createForm($this->getFormType(), $user);
    //     $this->form->handleRequest($request);


    //     if ($this->form->isSubmitted() && $this->form->isValid()) {
    //         /** @var string $plainPassword */
    //         $plainPassword = $this->form->get('plainPassword')->getData();

    //         // encode the plain password
    //         $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

    //         $user->setRoles($this->getRoles());

    //         dd($user);

    //         // $cart = new Cart();

    //         // $cart->setCustomer($user->getCustomer());


    //         $this->entityManager->persist($user);
    //         // $this->entityManager->persist($cart);
    //         $this->entityManager->flush();

    //         $this->addFlash('success', 'You have successfully registered');

    //         //Verification
    //         $signatureComponents = $this->verifyEmailHelper->generateSignature('app_verify_email', $user->getId(), $user->getEmail(), ['id' => $user->getId()]);
    //         $this->addFlash('success', 'Confirm your email at :' . $signatureComponents->getSignedUrl());
    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('app_login');
    //     }
    //     return $this->form;
    // }

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
