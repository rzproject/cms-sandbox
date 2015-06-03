<?php

namespace GMI\Bundle\RecommendationBundle\Model;


interface CloudInterface
{
    /**
     * @return mixed
     */
    public function getUid();

    /**
     * @param mixed $uid
     */
    public function setUid($uid);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt);
}