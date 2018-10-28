<?php
/**
 * Parameter to uses for Soap and Rest call.
 * Go in the Magento BackOffice to generate a soap token (in system > integration)
 *
 * @author    Laurent Minguet <lamin@smile.fr>
 * @copyright 2016 Smile
 */
$params = [
    'main_url' => 'http://magento2.lxc/',
    'rest_account' => [
        'username' => 'admin',
        'password' => 'admin12',
    ],
    'soap_token' => '78qkaeoo713iu38xly8rge2gn9qjvqkr',
    'exception_on_error' => false,
];
