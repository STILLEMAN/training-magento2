<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 26/09/17
 * Time: 16:54
 */

namespace Training\Helloworld\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Categories extends \Magento\Framework\App\Action\Action
{

    protected $productCollectionFactory;
    protected $categoryCollectionFactory;

    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory )
    {
        parent::__construct($context);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     *
     */
    public function execute()
    {
        $productCollection = $this->getAllProductsByName('bag');
        $catCollection = $this->getCategoryFromCollectionProducts($productCollection);

        $html = "";
        foreach($productCollection as $product){
            $html.= '<li>';
            $html.= $product->getId().' => '.$product->getSku().' => '.$product->getName();
            $html.= '<ul>';
            foreach ($product->getCategoryIds() as $categoryId) {
                /** @var \Magento\Catalog\Model\Category $category */
                $category = $catCollection->getItemById($categoryId);
                $html.= '<li>'.$categoryId.' => '.$category->getName().'</li>';
            }
            $html.= '</ul>';
            $html.= '</li>';
        }
        $html.= '</ul>';

        $this->getResponse()->appendBody($html);
    }


    private function getAllProductsByName($name)
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection
            ->addAttributeToFilter('name',array('like'=>"%$name%"))
            ->addCategoryIds()
            ->load();
        return $productCollection;

    }


    private function getCategoryFromCollectionProducts($collectionProduct)
    {
        $categoryIds = [];
        foreach ($collectionProduct->getItems() as $product) {
            /** @var \Magento\Catalog\Model\Product $product */
            $categoryIds = array_merge($categoryIds, $product->getCategoryIds());
        }
        $categoryIds = array_unique($categoryIds);
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $catCollection */
        $catCollection = $this->categoryCollectionFactory->create();
        $catCollection
            ->addAttributeToFilter('entity_id', array('in' => $categoryIds))
            ->addAttributeToSelect('name')
            ->load();
        return $catCollection;
    }
}