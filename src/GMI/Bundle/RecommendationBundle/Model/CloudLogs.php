<?php

namespace GMI\Bundle\RecommendationBundle\Model;


class CloudLogs implements CloudLogsInterface
{
    protected $referenceId;
	protected $type;
    protected $event;
    protected $points;
    protected $createdAt;
    protected $updatedAt;

	/**
	 * @return mixed
	 */
	public function getReferenceId()
	{
		return $this->referenceId;
	}

	/**
	 * @param mixed $referenceId
	 */
	public function setReferenceId($referenceId)
	{
		$this->referenceId = $referenceId;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}