<?php
/**
 * Magento 2 Training Project
 * Module Training/Helloworld
 */
namespace Training\Helloworld\Controller\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Action\Context;

/**
 * Action: Product/Index
 *
 * @author Laurent MINGUET <lamin@smile.fr>
 * @copyright 2016 Smile
 */
class Index extends \Magento\Framework\App\Action\Action
{


    protected $productFactory;


    public function __construct(Context $context, ProductFactory $productFactory)
    {
        parent::__construct($context);
        $this->productFactory = $productFactory ;
    }

    /**
     * Execute the action
     *
     * @return void
     */
    public function execute()
    {
        $product_id = $this->getRequest()->getParam('id');
        $product = $this->productFactory->create();//->load($product_id);
        $product->getResource()->load($product, $product_id);

        if($product->getId()){
            $this->getResponse()->appendBody('Product : ' . $product->getName());
        }else{
            $this->getResponse()->appendBody('Product not found');
        }
    }
}