<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 26/09/17
 * Time: 14:34
 */

namespace Training\Helloworld\Plugin\Model\Data;


class Customer
{
    /**
     * Hook la fonction setFirstname de Customer.
     * On reçoit toujours l'objet en 1er argument et ensuite les arguments de la fonction d'origine
     *
     * @param \Magento\Customer\Model\Data\Customer $customer
     * @param $firstname
     * @return array
     */
    public function beforeSetFirstname(\Magento\Customer\Model\Data\Customer $customer, $firstname)
    {
        return [ucwords($firstname)];
    }
}