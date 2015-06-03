<?php

namespace GMI\Bundle\RecommendationBundle\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    /**
     * @var array;
     */
	protected $options;
	
	public function __construct(array $options = array())
	{
		$resolver = new OptionsResolver();
		$resolver->setDefaults(
				array('enabled' => true,
					  'page_view_threshold' => 3,
					  'page_routes' => array()
		));

		$this->options = $resolver->resolve($options);
	}
	
	/**
	 * Check if enabled
	 * @return boolean
	 */
	public function isEnabled()
	{
		return (bool) $this->options['enabled'];
	}
	
	/**
	 * Get the maximum page view threshold
	 * @return integer
	 */
	public function getPageViewThreshold()
	{
		return (int) $this->options['page_view_threshold'];
	}
	
	/**
	 * Get all allowed page routes
	 * @return array
	 */
	public function getPageRoutes()
	{
		return (array) $this->options['page_routes'];
	}
	
	/**
	 * Check if route is allowed
	 * @param string $routeName
	 * @return boolean
	 */
	public function isAllowedInRoute($routeName = '')
	{
		return (bool) in_array($routeName, $this->getPageRoutes());
	}
}
