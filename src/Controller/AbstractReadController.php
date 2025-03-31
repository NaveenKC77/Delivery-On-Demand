<?php

namespace App\Controller;

use App\Services\PaginatorServiceInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractReadController extends AbstractController
{
    public function __construct(private PaginatorServiceInterface $paginatorService)
    {
    }

    /**
     * Constants for logs and errors
     */
    protected const SUCCESS = "success";
    protected const ERROR = "error";

    /**
     * Twig template to render response
     * @var string
     */
    private string $templateName = '';

    /**
     * Get twig template path to render
     * @return string
     */
    protected function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * Set Template Name for rendering
     * @param string $templateName
     * @return AbstractReadController
     */
    protected function setTemplateName(string $templateName): static
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * Template Data for twig rendering
     * @var array
     */
    private array $templateData = [];

    /**
     * Get template data for twig rendering
     * @return array
     */
    protected function getTemplateData(): array
    {
        return $this->templateData;
    }

    /**
     * Set template data for twig rendering
     * @param array $templateData
     * @return AbstractReadController
     */
    protected function setTemplateData(array $templateData): static
    {
        $this->templateData = $templateData;
        return $this;
    }



    /**
     * Get Pagination Object for the display
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int $currentPage
     * @param int $maxPerPage
     * @return \Pagerfanta\Pagerfanta
     */
    public function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage)
    {
        return $this->paginatorService->getPagination($qb, $currentPage, $maxPerPage);
    }


    public function read(): Response
    {
        return $this->render($this->getTemplateName(), $this->getTemplateData());
    }

    // /**
    //  * Base Page to List all the Entities
    //  * @param \Symfony\Component\HttpFoundation\Request $request
    //  * @return void
    //  */
    // public function index(Request $request): Response{
    //       // get wb for all items
    //       $qb = $this->getEntityServiceType()->getAllQueryBuilder();

    //       // get pagination
    //       $pagination = $this->getPagination($qb,1,10);

    //       $this-
    //       //render twig
    //       return $this->render($this->templateName,[
    //         'pager' => $pagination,
    //       ]);

    // }
    // /**
    //  * Page to SHow Single Item
    //  * @param \Symfony\Component\HttpFoundation\Request $request
    //  * @return void
    //  */
    // public function show(Request $request, int $id): Response{
    //     //get single item to be displayed
    //     $item = $this->getEntityServiceType()->getOneById($id);

    //     return $this->render($this->templateName,[
    //         $this->getEntityName() => $item
    //     ]);


    // }
}
