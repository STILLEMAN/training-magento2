<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 28/09/17
 * Time: 09:22
 */

namespace Training\Seller\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Training\Seller\Model\Seller;

class Url extends AbstractHelper
{
    /**
     * @return string
     */
    public function getSellersUrl()
    {
        return $this->_urlBuilder->getDirectUrl('sellers.html');
    }

    /**
     * @param Seller $seller
     * @return string
     */
    public function getSellerUrl($identifier)
    {
        return $this->_urlBuilder->getDirectUrl('seller/'. $identifier .'.html');
    }
}