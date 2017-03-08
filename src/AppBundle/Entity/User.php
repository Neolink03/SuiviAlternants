<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}

