<?php 

namespace PHPAzureUpload;

use MicrosoftAzure\Storage\Blob\Models\ListContainersOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class Container
{
    private $proxy;
    protected $containers;
    protected $newContainer;

    public function __construct($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * Get the value of proxy
     */ 
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * Get the value of containers
     */ 
    public function getContainers()
    {
        $this->containers = array();
    
        try {
            
            $listContainersOptions = new ListContainersOptions;
            $listContainersResult = $this->proxy->listContainers($listContainersOptions);
            
            foreach ($listContainersResult->getContainers() as $container)
            {
                $this->containers[] = $container->getName();
            }

        } catch (ServiceException $e) {
            return $e;
        }
            
	    return $this->containers;
    }

    /**
     * Get the value of newContainer
     */ 
    public function getNewContainer()
    {
        return $this->newContainer;
    }

    /**
     * Set the value of newContainer
     *
     * @return  self
     */ 
    public function setNewContainer($newContainer)
    {
        if ( !in_array($newContainer, $this->getContainers()) ) {
            $this->createContainer($newContainer);
        }

        $this->newContainer = $newContainer;

        return $this;
    }

    private function createContainer($name)
    {
        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        
		try {
	        // Create container.
	        $this->proxy->createContainer($name, $createContainerOptions);
	    } catch (ServiceException $e) {
	        return $e;
	    }
	}
}