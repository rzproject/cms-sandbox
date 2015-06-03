<?php

namespace GMI\Bundle\RecommendationBundle\Model;

/**
 * Cloudable Interface
 */
interface CloudableInterface
{
	public function getId();
	public function getName();
	public function getParent();
}
