<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 27/09/17
 * Time: 13:40
 */

namespace Training\Seller\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Training\Seller\Model\Seller;
use Training\Seller\Model\SellerFactory; //pas créer, il est crée par le di ?

class InstallDataMe implements InstallDataInterface
{
    /**
     * @var SellerFactory
     */
    protected $sellerFactory;


    public function __construct(SellerFactory $sellerFactory)
    {
        $this->sellerFactory = $sellerFactory;
    }


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var Seller $seller*/
        $seller = $this->sellerFactory->create();
        $seller
            ->setIdentifier('test')
            ->setName('name test');
        $seller->getRessource()->save($seller);
    }

}