<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 26/09/17
 * Time: 15:05
 */

namespace Training\Helloworld\Rewrite\Model;


class Product extends \Magento\Catalog\Model\Product
{

    /**
     * @return string
     */
    public function getName()
    {

        return parent::getName(). ' Hello World ';
    }

}