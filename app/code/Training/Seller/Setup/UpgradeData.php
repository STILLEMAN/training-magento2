<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 28/09/17
 * Time: 15:57
 */

namespace Training\Seller\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Catalog\Model\Product;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Model\Customer;


class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavConfig
     */
    protected $eavConfig;

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    protected $eavSetupFactory;

    public function __construct(
        EavConfig $eavConfig,
        CustomerSetupFactory $customerSetupFactory,
        EavSetupFactory      $eavSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->addCustomerAttribute($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $this->addProductAttribute($setup);
        }

        $setup->endSetup();

    }


    /**
     * Add the attribute "training_seller_id" on the customer model
     *
     * @param ModuleDataSetupInterface $setup
     *
     * @return void
     */
    protected function addCustomerAttribute(ModuleDataSetupInterface $setup)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            'training_seller_id',
            [
                'label'    => 'Training Seller',
                'type'     => 'int',//sous-table de mysql
                'input'    => 'select',
                'source'   => \Training\Seller\Option\Seller::class,
                'required' => false,
                'system'   => false,//attributs crées par magento à l'init
                'position' => 900,
            ]
        );

        $this->eavConfig->clear();// vide cache EAV

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'training_seller_id');
        $attribute->setData('used_in_forms', ['adminhtml_customer']);
        $attribute->save();

        $this->eavConfig->clear();
    }


    protected function addProductAttribute(ModuleDataSetupInterface $setup)
    {

        // voir BO Attribut set et product dans STORE
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            'training_seller_ids',
            [
                'label'                    => 'Training Sellers',// BO
                'type'                     => 'varchar',//cible la table EAV
                'input'                    => 'multiselect', // selection de plusieurs seller pour un product
                'backend'                  => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class, // Façon dont la donnée sera transformé avant sauvegarde en BDD
                'frontend'                 => '',// Façon dont la donnée sera transformé avant sauvegarde en BDD, ici pas de sauvegarde en FRONT
                'class'                    => '',// class CSS affichage product BO
                'source'                   => \Training\Seller\Option\Seller::class, //remplir le Select
                'global'                   => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL, // botre attribut doit être GLOBAL (scope [store, website,global])
                'visible'                  => true, //visible en BO ?
                'required'                 => false, //obligatoire ?
                'user_defined'             => true, //
                'default'                  => '', // valeur par default si vide
                'searchable'               => false, // Recherche en front
                'filterable'               => false, // Filtre sur la recherche
                'comparable'               => true, // peux utiliser cet attribut pour le comparateur ?
                'visible_on_front'         => true,// visible en FRONT ?
                'used_in_product_listing'  => false, // page view FO
                'is_html_allowed_on_front' => true,
                'unique'                   => false,//Unique ?
                'apply_to'                 => 'simple,configurable' //type de product que l'on veux l'appliquer
            ]
        );

        // Créer un group sur le product, attribut set BAG concerné
        $eavSetup->addAttributeGroup(
            Product::ENTITY,
            'bag',
            'Training'
        );

        $eavSetup->addAttributeToGroup(
            Product::ENTITY,
            'bag',
            'Training',
            'training_seller_ids'
        );

        $this->eavConfig->clear();
    }
}