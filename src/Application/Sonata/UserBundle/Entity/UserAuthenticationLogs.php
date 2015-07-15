<?php

namespace Application\Sonata\UserBundle\Entity;

use Rz\UserBundle\Entity\BaseUserAuthenticationLogs;

class UserAuthenticationLogs extends BaseUserAuthenticationLogs
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
}