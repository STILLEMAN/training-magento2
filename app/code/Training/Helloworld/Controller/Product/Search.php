<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 27/09/17
 * Time: 09:42
 */

namespace Training\Helloworld\Controller\Product;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Store\Api\Data\GroupInterface;

class Search extends \Magento\Framework\App\Action\Action
{

    protected $productRepositoryInterface;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $sortOrderBuilder;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        SortOrderBuilder $sortOrderBuilder
    )
    {
        parent::__construct($context);
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function execute()
    {
        $products = $this->getListSearch('%bruno%','%comfortable%');
        //$this->getResponse()->appendBody("Search");
        foreach ($products as $product) {
            $this->getResponse()->appendBody($product->getName() . "(" . $product->getSku() .")" . "<br/>");
        }
    }


    private function getListSearch($name,$description)
    {
        $nameFilter = $this->filterBuilder
            ->setField('name')
            ->setConditionType('like')
            ->setValue($name)
            ->create();

        $descriptionFilter = $this->filterBuilder
            ->setField('description')
            ->setConditionType('like')
            ->setValue($description)
            ->create();
        $sortOrder = $this->sortOrderBuilder
            ->setField('name')
            ->setDirection(\Magento\Framework\Api\SortOrder::SORT_DESC)
            ->create();
        $criteria = $this->searchCriteriaBuilder
            ->addFilters([$nameFilter])
            ->addFilters([$descriptionFilter])
            ->addSortOrder($sortOrder)
            ->setPageSize(6)
            ->setCurrentPage(2)
            ->create();


        return $this->productRepositoryInterface->getList($criteria)->getItems();
    }






}