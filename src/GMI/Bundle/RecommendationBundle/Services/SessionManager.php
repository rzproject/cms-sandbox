<?php

namespace GMI\Bundle\RecommendationBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use GMI\Bundle\RecommendationBundle\Model\CloudableInterface;
use GMI\Bundle\RecommendationBundle\Model\CloudInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class SessionManager
{
	const COOKIE_NAME             = "cloud-uid";
	const CLOUD_CATEGORY_KEY      = "categories";
	const CLOUD_CATEGORY_ROOT_KEY = "categories_root";
	const CLOUD_TAG_KEY           = "tags";
	const CLOUD_TAG_ROOT_KEY      = "tag_root";
	const CLOUD_SURVEY_KEY        = "survey";
	const BASE_CATEGORY_WEIGHT    = 1;
	const BASE_TAG_WEIGHT         = 1;

    protected $config;
    protected $session;
    protected $request;
    protected $tokenStorage;
	protected $authorizationChecker;
    protected $cloudManager;
    protected $cloudCategoryManager;
	protected $cloudTagManager;
    protected $cloudLogsManager;

	protected $container;
	protected $cloud;
	protected $categoryData;
	protected $tagData;
	protected $dataKeyValueProcessed;
	protected $cloudType;
	protected $pioEventServer;
	protected $pioRecommendationClient;

	/**
	 * Default constructor
	 *
	 * @param SessionInterface $session
	 * @param RequestStack $request
	 * @param Config $config
	 * @param SecurityContextInterface $securityContext
	 * @param ManagerInterface $cloudManager
	 * @param ManagerInterface $cloudCategoryManager
	 * @param ManagerInterface $cloudTagManager
	 * @param ManagerInterface $cloudLogsManager
	 */
    public function __construct(SessionInterface $session,
                                RequestStack $request,
                                Config $config,
                                TokenStorageInterface $tokenStorage,
                                AuthorizationCheckerInterface $authorizationChecker,
                                ManagerInterface $cloudManager,
                                ManagerInterface $cloudCategoryManager,
                                ManagerInterface $cloudTagManager,
                                ManagerInterface $cloudLogsManager,
                                $pioEventServer = null,
								$pioRecommendationClient = null)
    {

        # Recommendation Config
        $this->config = $config;
        # Session
        $this->session = $session;
        # Request
        $this->request = $request;
        # token storage
        $this->tokenStorage = $tokenStorage;
	    # token storage
	    $this->authorizationChecker = $authorizationChecker;
        # Cloud Manager
        $this->cloudManager = $cloudManager;
        # Cloud Category Manager
        $this->cloudCategoryManager = $cloudCategoryManager;
	    # Cloud Tag Manager
	    $this->cloudTagManager = $cloudTagManager;
        # Cloud Logs Manager
        $this->cloudLogsManager = $cloudLogsManager;
		# category cloud
		$this->cloud      = null;
		# category cloud weight mapping container
		$this->categoryData       = array(self::CLOUD_CATEGORY_KEY => array());
		// populate category cloud mapping
		$this->loadCloudCategories();
	    # tag cloud weight mapping container
	    $this->tagData       = array(self::CLOUD_TAG_KEY => array());
	    // populate tag cloud mapping
	    $this->loadCloudTags();
		// container to make sure categories are processed only once per request.
		$this->dataKeyValueProcessed = array();
	    $this->cloudType = array('category', 'tag');

	    $this->pioEventServer =$pioEventServer;
	    $this->pioRecommendationClient = $pioRecommendationClient;
    }
    
    /**
     * Link cloud to 

    /**
     * Return the current cloud
     *
     * @return CloudInterface
     */
	public function getCloud()
	{
		return $this->cloud;
	}

	public function fetchRawProfile($type = 'categories') {
		return $this->getProfileData($type);
	}

	public function fetchArrayProfile($type = 'categories') {
		$categories = $this->getProfileData($type);
		if(!$categories) {
			return null;
		}
		return $this->buildArrayProfileData($categories);
	}

	protected function getProfileData($type = 'categories') {
		$data = $this->getCloud()->getData();
		if(!isset($data[$type])) {
			return null;
		}
		$categories=$data[$type];

		if(is_array($categories)) {
			arsort($categories);
			return $categories;
		}

		return null;

	}

	protected function buildArrayProfileData($data = array()) {
		$temp = array();
		if(is_array($data)) {
			foreach($data as $id=>$score) {
				$temp[] = array('id'=>$id, 'score'=>$score);
			}
			return $temp;

		} else {
			return null;
		}
	}
	
	/**
	 * Profiling configuration wrapper
	 * 
	 * @return \GMI\Bundle\RecommendationBundle\Services\Config
	 */
	public function getConfig()
	{
		return $this->getConfigService();
	}
    
	/**
	 * Load the category config array mapping
	 * 
	 * @return null
	 */
    public function loadCloudCategories()
    {
		if (($cloudCategories = $this->getCloudCategoryManagerService()->getActiveCloudCategories())) {
			foreach ($cloudCategories as $cloudCategory) {
				$this->categoryData[self::CLOUD_CATEGORY_KEY][$cloudCategory['id']] = array('priority' => (int)$cloudCategory['priority'], 'event' => $cloudCategory['slug']);
			}
		}
	}

	/**
	 * Load the category config array mapping
	 *
	 * @return null
	 */
	public function loadCloudTags()
	{
		if (($cloudTags = $this->getCloudTagManagerService()->getActiveCloudTags())) {
			foreach ($cloudTags as $cloudTag) {
				$this->tagData[self::CLOUD_TAG_KEY][$cloudTag['id']] = array('priority' => (int)$cloudTag['priority'], 'event' => $cloudTag['slug']);
			}
		}
	}

	/**
	 * Check if category is in category cloud config
	 * category cloud config holds the category weight. This can be
	 * defined in the backend
	 *
	 * @param string $key
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isExistInCloudConfig($key, $type = 'category')
	{
		if($type == 'category') {
			if (isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$key])) {
				return true;
			}
		} elseif($type == 'tag') {
			if (isset($this->tagData[self::CLOUD_TAG_KEY][$key])) {
				return true;
			}
		}
		return false;
	}

    protected function hasPriorityEvent($id , $event = 'view', $type = 'category') {

	    if($type == 'category') {
		    if (isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$id])) {
			    if(isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$id]['event'])) {
				    return ($this->categoryData[self::CLOUD_CATEGORY_KEY][$id]['event'] === $event) ? true : false;
			    }
		    }
	    } elseif($type == 'tag') {
		    if (isset($this->tagData[self::CLOUD_TAG_KEY][$id])) {
			    if(isset($this->tagData[self::CLOUD_TAG_KEY][$id]['event'])) {
				    return ($this->tagData[self::CLOUD_TAG_KEY][$id]['event'] === $event) ? true : false;
			    }
		    }
	    }
        return false;
    }
	
	/**
	 * Get the category priority value
	 * 
	 * @param integer $id
	 * @return float
	 */
	protected function getCategoryPriorityValue($id)
	{
		if (isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$id])) {
			return (float) (isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$id]['priority'])?$this->categoryData[self::CLOUD_CATEGORY_KEY][$id]['priority']:0);
		}
		return 0;
	}

	protected function getTagPriorityValue($id)
	{
		if (isset($this->tagData[self::CLOUD_TAG_KEY][$id])) {
			return (float) (isset($this->tagData[self::CLOUD_TAG_KEY][$id]['priority'])?$this->tagData[self::CLOUD_TAG_KEY][$id]['priority']:0);
		}
		return 0;
	}

	/**
	 * Get the category priority value
	 *
	 * @param integer $id
	 * @param string $type
	 *
	 * @return float
	 */
    protected function getPriorityValue($id, $type = 'category')
    {
	    if ($type == 'category') {
		    return $this->getCategoryPriorityValue($id);
	    } elseif($type == 'tag') {
		    return $this->getTagPriorityValue($id);
	    }
        return 0;
    }
    
	/**
	 * Load current cloud usually called in the kernel request event
	 * 
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
    public function loadCloud()
    {
		if (!$this->getConfig()->isEnabled()) {
			return false;
		}
		
		$request = $this->getRequestStackService()->getCurrentRequest();
		$this->referer = $request->headers->get('referer');
		
		if ($this->cloud) {
			return $this->cloud;
		}
		
		if ($this->isUserLoggedIn()) {
			$this->cloud = $this->getUserCloud();
		} else {
			$this->cloud = $this->getSessionCloud();
		}

		return $this->cloud;
	}
	
	/**
	 * Update cloud, usually this is called in the kernel response event
	 * 
	 * @param \Symfony\Component\HttpFoundation\Response $response
	 */
	public function updateCloud(Response $response = null)
	{
		$request      = $this->getRequestStackService()->getCurrentRequest();
		$enabled      = $this->getConfig()->isEnabled();
		$allowedRoute = $this->getConfig()->isAllowedInRoute($request->get('_route'));

		if (!($enabled && $allowedRoute)) {
			return false;
		}

		if (!$this->cloud) {
			return;
		}

		$cloud = $this->cloud;
		$cookieName = $this->getKeyName();
		if ($cloud) {
			if (!($uid = $this->getRequestStackService()->getCurrentRequest()->cookies->get($cookieName))) {
				// create the cloud cookie if not exist
				$cookie = new Cookie($cookieName, $cloud->getUid(), $this->getCookieLifetime());
				if (!$response) {
					$response = new Response();
				}
				$response->headers->setCookie($cookie);
			}
			if ($this->hasCloudDataForUpdate()) {
				// persist cloud to storage
				$cloudManager = $this->getCloudManagerService();
				$cloudManager->save($cloud);
			}
		}
	}
	
	public function linkSessionCloudToUser(UserInterface $user)
	{
		$enabled = $this->getConfig()->isEnabled();
		if ($enabled && ($cloud = $this->cloud)) {
			if (!$cloud->getUser()) {
				$cloudManager = $this->getCloudManagerService();
				$user = $this->getLoggedInUser();
				$cloud->setUser($user);
				$cloud->setUid(null);
				$cloudManager->save($cloud);
			}
		}
	}
	
	/**
	 * Return the current category cloud data
	 * 
	 * @return array
	 */
	public function getCategoryCloudData()
	{
		$sorted = array();
		if ($this->cloud) {
			$sorted = $this->cloud->getData();
			if (isset($sorted[self::CLOUD_CATEGORY_KEY])) {
				$sorted = $sorted[self::CLOUD_CATEGORY_KEY];
			}
			if(is_array($sorted)) {
				arsort($sorted, SORT_NUMERIC);
			}

		}
		return $sorted;
	}

	/**
	 * Return the current category cloud data
	 *
	 * @return array
	 */
	public function getTagCloudData()
	{
		$sorted = array();
		if ($this->cloud) {
			$sorted = $this->cloud->getData();
			if (isset($sorted[self::CLOUD_TAG_KEY])) {
				$sorted = $sorted[self::CLOUD_TAG_KEY];
			}
			if(is_array($sorted)) {
				arsort($sorted, SORT_NUMERIC);
			}

		}
		return $sorted;
	}
	
	/**
	 * Return the current root category cloud data
	 * 
	 * @return array
	 */
	public function getRootCategoryCloudData()
	{
		$sorted = array();
		if ($this->cloud) {
			$sorted = $this->cloud->getData();
			if (isset($sorted[self::CLOUD_CATEGORY_ROOT_KEY])) {
				$sorted = $sorted[self::CLOUD_CATEGORY_ROOT_KEY];
			}
			arsort($sorted, SORT_NUMERIC);
		}
		return $sorted;
	}
	
	/**
	 * Get the current user profile cloud
	 * 
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
	protected function getUserCloud()
	{
		$user = $this->getLoggedInUser();
		if (($cloud = $this->getCloudManagerService()->findOneBy(array('user' => $user)))) {
			return $cloud;
		} else {
			return $this->rebuildUserCloud($user);
		}
	}
	
	/**
	 * Get the current session cloud based from the cookie uid found
	 * or rebuild the cloud if not found.
	 * 
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
	protected function getSessionCloud()
	{
		$cookieName = $this->getKeyName();
		if (!($uid = $this->getRequestStackService()->getCurrentRequest()->cookies->get($cookieName))) {
			$newUid = $this->generateToken();
			$cloud  = $this->initCloud($newUid);
		} else {
			$cloud = $this->getCloudManagerService()->findOneBy(array('uid' => $uid));
			if (!$cloud) {
				$cloud = $this->rebuildCloud($uid);
			}
		}
		return $cloud;
	}
	
	/**
	 * Init cloud
	 * 
	 * @param string $uid
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
	protected function initCloud($uid)
	{
		$cloudManager = $this->getCloudManagerService();
		$cloud = $cloudManager->create();
		$cloud->setUid($uid);
		return $cloud;
	}
	
	/**
	 * Rebuild the db cloud using the uid
	 * 
	 * @param string $uid
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
	protected function rebuildCloud($uid)
	{
		die('here');
		$this->pioEventServer->createUser($uid);
		$cloudManager = $this->getCloudManagerService();
		$cloud = $cloudManager->create();
		$cloud->setUid($uid);
		$cloudManager->save($cloud);
		return $cloud;
	}

	/**
	 * Rebuild user cloud
	 * 
	 * @param UserInterface $user
	 * @return \GMI\Bundle\RecommendationBundle\Entity\CloudInterface
	 */
	protected function rebuildUserCloud($user)
	{
		$this->pioEventServer->createUser($user->getId());
		$cloudManager = $this->getCloudManagerService();
		$cloud = $cloudManager->create();
		$cloud->setUser($user);
		$cloudManager->save($cloud);
		return $cloud;
	}

	/**
	 * Update cloud data
	 *
	 * @param $type
	 * @param $cloudData
	 * @param string $event
	 *
	 * @return null
	 */
    public function cloudData($type, $cloudData, $event = 'view')
    {
        if (!$this->getConfig()->isEnabled()) {
            return false;
        }

	    if(!in_array($type = strtolower($type), $this->cloudType)) {
		    return false;
	    }

        if (($cloud = $this->cloud)) {
            if(is_array($cloudData)) {
                foreach($cloudData as $cData) {
                    if (empty($cData)) {
                        continue;
                    }
                    $this->updateCloudData($type, $cData, $event);
                }
            } else {
                $this->updateCloudData($type, $cloudData, $event);
            }
        }
    }


    protected function updateCloudData($type, $cData, $event) {
	    #TODO: add support for Notification Bundle
	    $cloud = $this->cloud;
        $key = $type == 'tag' ? self::CLOUD_TAG_KEY : self::CLOUD_CATEGORY_KEY;
        $data = $cloud->getData();

        foreach(array(self::CLOUD_CATEGORY_KEY, self::CLOUD_TAG_KEY) as $tempKey) {
	        if (!isset($data[$tempKey])) {
		        $data[$tempKey] = array();
	        }
        }

        $value = $type == 'tag' ? self::BASE_TAG_WEIGHT : self::BASE_CATEGORY_WEIGHT;

        if ($this->isExistInCloudConfig($cData, $type)) {
	        $value += (float)$this->getPriorityValue($cData, $type);
        }

        $tempData = $data[$key];

        $temp = $this->updateDataKeyValue($cData, $value, $tempData);
        $data[$key] = $temp;
        $this->logCloudData($cData, $value, $event, $this->cloud, $type);
        $this->cloud->setData($data);

		if ($this->hasCloudDataForUpdate()) {
			// persist cloud to storage
			$cloudManager = $this->getCloudManagerService();
			$cloudManager->save($this->cloud);
		}


    }

	public function updateDataKeyValue($cData, $value, $tempData)
	{
		if($tempData) {
			if(array_key_exists($cData, $tempData)) {
				$tempData[$cData] = (float)$tempData[$cData]+=$value;
			} else {
				$tempData[$cData] = (float)$value;
			}
		} else {
			$tempData = array($cData=>(float)$value);
		}

		return $tempData;
	}

    protected function logCloudData($cData, $value, $event, $cloud, $type = 'category') {
	    $logs = $this->cloudLogsManager->create();
	    $logs->setReferenceId($cData);
        $logs->setCloud($cloud);
        $logs->setEvent($event);
        $logs->setPoints($value);
	    $logs->setType($type);
        $this->cloudLogsManager->save($logs);
    }

    /**
     * Check if category is in category cloud config
     * category cloud config holds the category weight. This can be
     * defined in the backend
     *
     * @param string $key
     * @return boolean
     */
    protected function isCloudData($key)
    {
        if (isset($this->categoryData[self::CLOUD_CATEGORY_KEY][$key])) {
            return true;
        }
        return false;
    }

    /**
     * Update cloud data starting from the current category
     * traversing all parent categories and applying the priority values
     * based from the defined cloud category.
     *
     * @param \GMI\Bundle\RecommendationBundle\Entity\CloudableInterface|CloudableInterface $category
     * @param object $refCategory
     * @param array $data
     * @return null
     */
//	public function updateCategoryCloudData(CloudableInterface $category, $refCategory = null, &$data = null)
//	{
//		if (!$this->getConfig()->isEnabled()) {
//			return false;
//		}
//		if (($cloud = $this->cloud)) {
//
//			if (!($data)) {
//				$data = $cloud->getData();
//				if (!isset($data[self::CLOUD_CATEGORY_KEY])) {
//					$data[self::CLOUD_CATEGORY_KEY] = array();
//				}
//				$data = $data[self::CLOUD_CATEGORY_KEY];
//			}
//
//			$key = $category->getId();
//
//			if (empty($key)) {
//				return;
//			}
//
//			$value = self::BASE_CATEGORY_WEIGHT;
//			// check if current category is defined in the category cloud config
//			if ($this->isExistInCloudConfig($key)) {
//				// add defined priority value for this category
//				$value += (float)$this->getCategoryPriorityValue($key);
//				// we need to pass this category as our reference category in case
//				// our parent categories are not yet defined in the category cloud config
//				$refCategory = $category;
//				// update the cloud data using the derived value
//				$this->updateDataKeyValue($data);
//			} else {
//				// basically this parent category has not been defined in the category cloud config,
//				// use the passed child reference category priority value instead
//				//if ($refCategory && $this->isExistInCloudConfig($refCategory->getId())) {
//				if ($refCategory && $refCategory->getId()) {
//					$value += (float)$this->getCategoryPriorityValue($refCategory->getId());
//				}
//				$this->updateDataKeyValue($data, $key, $value, ($category->getParent()==null?true:false));
//			}
//
//			// make sure we traverse all parent categories up to its root
//			if ($category->getParent()) {
//				$this->updateCategoryCloudData($category->getParent(),$refCategory, $data);
//			}
//		}
//	}
	
	/**
	 * Update data key value pair
	 * 
	 * @param array $data
	 * @param string $key
	 * @param float $value
	 */
//	public function updateDataKeyValue(&$data, $key, $value, $isRootCategory)
//	{
//		if (isset($data[$key])) {
//			$data[$key] += $value;
//		} else {
//			$data[$key] = $value;
//		}
//		$cloudData = $this->cloud->getData();
//		$cloudData[self::CLOUD_CATEGORY_KEY] = $data;
//		if ($isRootCategory) {
//			$cloudData[self::CLOUD_CATEGORY_ROOT_KEY][$key] = $data[$key];
//		}
//		$this->cloud->setData($cloudData);
//	}
	
	/**
	 * Check if there is a need to persist the category cloud
	 * 
	 * @return boolean
	 */
	protected function hasCloudDataForUpdate()
	{
		if (($cloud = $this->cloud)) {
			$data = $cloud->getData();
			return count($data);
		}
		return false;
	}
	
	/**
	 * Get default key name
	 * 
	 * @return integer
	 */
	protected function getKeyName()
	{
		return sprintf("%s-%s", $this->getSessionService()->getName(), self::COOKIE_NAME);
	}
	
	/**
	 * Default cookie lifetime (1 Week)
	 * 
	 * @return integer
	 */
	protected function getCookieLifetime()
	{
		return (time() + 3600 * 24 * 7);
	}
	
	/**
	 * Generate unique cloud token
	 * 
	 * @param string $seed
	 * @return string
	 */
	public static function generateToken($seed = '')
	{
		$seed = empty($seed) ? microtime() : $seed ;
		$bytes = hash('sha256', sprintf("%s:%s",uniqid(mt_rand(), true),$seed), true);
		return base_convert(bin2hex($bytes), 16, 36);
	}
	
	/**
	 * @return \GMI\Bundle\RecommendationBundle\Services\Config
	 */
	protected function getConfigService()
	{
		return $this->config;
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Session\Session
	 */
	protected function getSessionService()
	{
		return $this->session;
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\RequestStack
	 */
	protected function getRequestStackService()
	{
		return $this->request;
	}
	
	/**
	 * @return \Sonata\CoreBundle\Model\ManagerInterface
	 */
	protected function getCloudManagerService()
	{
		return $this->cloudManager;
	}
	
	/**
	 * @return \Sonata\CoreBundle\Model\ManagerInterface
	 */
	protected function getCloudCategoryManagerService()
	{
		return $this->cloudCategoryManager;
	}

	/**
	 * @return \Sonata\CoreBundle\Model\ManagerInterface
	 */
	protected function getCloudTagManagerService()
	{
		return $this->cloudTagManager;
	}
	
	/**
	 * Check if user is currently logged in
	 * 
	 * @return boolean
	 */
	protected function isUserLoggedIn()
	{
		try {
			if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
				return (bool)$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
			}
		} catch (\Exception $e) {
			return false;
		}
	}
	
	/**
	 * 
	 * @return \Sonata\UserBundle\Model\UserInterface
	 */
	protected function getLoggedInUser()
	{
		try {
			if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
				return $this->tokenStorage->getToken()->getUser();
			}
		} catch (\Exception $e) {
			return null;
		}
	}

	public function pioFetchRecommendation($itemCount) {

		if($this->isUserLoggedIn()) {
			$userId = $this->getLoggedInUser()->getId();
		} else {
			$userId = $this->cloud->getUid() ?: null;
		}
		$recommendation =$this->pioRecommendationClient->sendQuery(['user' => $userId, 'num' => $itemCount]);
		return $this->processRecommendation($recommendation);
	}

	public function pioFetchRecommendationByCategory($itemCount) {
		if($this->isUserLoggedIn()) {
			$userId = $this->getLoggedInUser()->getId();
		} else {
			$userId = $this->cloud->getUid() ?: null;
		}
		$categories = $this->getCategoryCloudData();

		if($categories && is_array($categories)) {
			$categories = array_keys($categories);
			$recommendation = $this->pioRecommendationClient->sendQuery(['user' => $userId, 'num' => $itemCount, 'categories'=>$categories]);
		} else {
			$recommendation = $this->pioRecommendationClient->sendQuery(['user' => $userId, 'num' => $itemCount]);
		}

		return $this->processRecommendation($recommendation);
	}

	public function pioFetchRecommendationByTag($itemCount) {
		if($this->isUserLoggedIn()) {
			$userId = $this->getLoggedInUser()->getId();
		} else {
			$userId = $this->cloud->getUid() ?: null;
		}
		$tags = $this->getTagCloudData();

		if($tags && is_array($tags)) {
			$tags = array_keys($tags);


			$recommendation = $this->pioRecommendationClient->sendQuery(['user' => $userId, 'num' => $itemCount, 'tags'=>$tags]);
		} else {
			$recommendation = $this->pioRecommendationClient->sendQuery(['user' => $userId, 'num' => $itemCount]);
		}
		return $this->processRecommendation($recommendation);
	}

	protected function processRecommendation($recomendations) {
		$reco = array();
		foreach($recomendations as $tempRecomendation) {
			foreach($tempRecomendation as $recomendation) {
				$reco[] = 	$recomendation['article'];
			}
		}

		return $reco;
	}

	public function pioSendUserAction($post, $type='view', $attributes = null) {
		if($this->isUserLoggedIn()) {
			$userId = $this->getLoggedInUser()->getId();
		} else {
			$userId = $this->cloud->getUid() ?: null;
		}
		if ($attributes) {
			$this->pioEventServer->recordUserActionOnItem($type, $userId, $post->getId(), array('categories'=>$attributes));
		} else {
			$this->pioEventServer->recordUserActionOnItem($type, $userId, $post->getId());
		}

		/** @var EventClient $eventClient */
		#$eventClient = $this->get('endroid.prediction_io.app_one.event_client');
		/** @var EngineClient $recommendationEngineClient */
		#$recommendationEngineClient = $this->get('endroid.prediction_io.app_one.recommendation.engine_client');


// Populate with users and items
#	    $eventClient->createUser($userId);
#	    $eventClient->createItem($itemId);

// Record actions
#	    $client->recordUserActionOnItem('view', $userId, $itemId);

// Return recommendations
//		$recommendedItems = $this->pioRecommendationClient->getRecommendedItems($this->getLoggedInUser()->getId(), 3);
//		$similar = $this->pioRecommendationClient->getSimilarItems($this->getLoggedInUser()->getId(), 3);
#	    $similarItems = $similarProductEngineClient->getSimilarItems($itemId, $itemCount);

//		dump($similar);
//		dump($recommendedItems);
//		die();
	}

	public function checkRecommendationServerConnection() {
		try {
			$this->pioRecommendationClient->getStatus();
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}

	/**
	 * @return null
	 */
	public function getPioRecommendationClient()
	{
		return $this->pioRecommendationClient;
	}

	/**
	 * @param null $pioRecommendationClient
	 */
	public function setPioRecommendationClient($pioRecommendationClient)
	{
		$this->pioRecommendationClient = $pioRecommendationClient;
	}

	/**
	 * @return null
	 */
	public function getPioEventServer()
	{
		return $this->pioEventServer;
	}

	/**
	 * @param null $pioEventServer
	 */
	public function setPioEventServer($pioEventServer)
	{
		$this->pioEventServer = $pioEventServer;
	}
}
