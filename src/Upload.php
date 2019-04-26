<?php 

namespace PHPAzureUpload;

use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListContainersOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class Upload
{
    private $connectionString;
	private $accountName;
	private $accountKey;
	private $accountContainer;
	private $accountUrl;
    private $proxy;
	private $containers;
	private $json;
    
    public function __construct($accountName, $accountKey, $accountContainer, $accountUrl, $json = false)
    {
        $this->accountName = $accountName;
        $this->accountKey = $accountKey;
		$this->accountUrl = $accountUrl;
		$this->json = $json;

        $this->connectionString = "DefaultEndpointsProtocol=http;AccountName=" . $this->getAccountName() . ";AccountKey=" . $this->getAccountKey() . ""; 

        $this->proxy = ServicesBuilder::getInstance()->createBlobService($this->connectionString);

        $this->setAccountContainer($accountContainer);
    }

    private function getContainers()
    {
        $this->containers = array();
    
        try {
            
            $listContainersOptions = new ListContainersOptions;
            $listContainersResult = $this->getProxy()->listContainers($listContainersOptions);
            
            foreach ($listContainersResult->getContainers() as $container)
            {
                $this->containers[] = $container->getName();
            }

        } catch (ServiceException $e) {
            return $e;
        }
            
	    return $this->containers;
    }

    public function setAccountContainer($accountContainer)
    {
        if ( !in_array($accountContainer, $this->getContainers()) ) {
            $this->createContainer($accountContainer);
        }

        $this->accountContainer = $accountContainer;
    }

    private function createContainer($name)
    {
        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        
		try {
	        // Create container.
	        $this->getProxy()->createContainer($name, $createContainerOptions);
	    } catch (ServiceException $e) {
	        return $e;
	    }
    }

    /**
     * Get the value of connectionString
     */ 
    public function getConnectionString()
    {
        return $this->connectionString;
    }

	/**
	 * Get the value of accountName
	 */ 
	public function getAccountName()
	{
		return $this->accountName;
	}

	/**
	 * Get the value of accountKey
	 */ 
	public function getAccountKey()
	{
		return $this->accountKey;
	}

	/**
	 * Get the value of accountContainer
	 */ 
	public function getAccountContainer()
	{
		return $this->accountContainer;
	}

	/**
	 * Get the value of accountUrl
	 */ 
	public function getAccountUrl()
	{
		return $this->accountUrl;
    }

    /**
     * Get the value of proxy
     */ 
    public function getProxy()
    {
        return $this->proxy;
    }

    public function sendFile($localFileName, $azureFileName)
	{
        $content = fopen($localFileName, "r");

        try {
				    
		    //Upload blob
		    $blb = $this->getProxy()->createBlockBlob($this->getAccountContainer(), $azureFileName, $content);

		    $file = $this->setUrlFile($azureFileName);
		    
		    $message = array(
		    	'status' => 'success',
		    	'fileBlobUrl' => $file['blob'],
		    	'fileUrl' => $file['file']
			);
			
			if ($this->json)
			return $this->sendJsonResponse($message);
			else 
			return $message;

		} catch(ServiceException $e) {
		    // Handle exception based on error codes and messages.
		    // Error codes and messages are here:
		    // http://msdn.microsoft.com/library/azure/dd179439.aspx
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

		    $message = array(
		    	'status' => 'error',
		    	'code' => $code,
		    	'message' => $error_message
			);
			
			if ($this->json)
			return $this->sendJsonResponse($message);
			else 
			return $message;
		}
    }

    private function setUrlFile($name)
	{
		return array(
			'blob' => 'http://' . $this->getAccountName() . '.blob.core.windows.net/' . $this->getAccountContainer() . '/' . $name,
			'file' => $this->getAccountUrl() . '/' . $this->getAccountContainer() . '/'. $name
		);
    }
    
    private function sendJsonResponse($message)
	{
		header('Content-Type: application/json');
		echo json_encode($message);
    }
    
    public function deleteFile($name)
	{
		try {
			$this->getProxy()->deleteBlob($this->getAccountContainer(), $name);

			$message = array(
		    	'status' => 'success',
		    	'code' => 200
		    );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

		    $message = array(
		    	'status' => 'error',
		    	'code' => $code,
		    	'message' => $error_message
		    );
		}

		if ($this->json)
		return $this->sendJsonResponse($message);
		else 
		return $message;
    }
    
    public function copyFile($source, $copy)
	{
		try {

			$this->getProxy()->copyBlob(
				$this->getAccountContainer(), $copy, 
				$this->getAccountContainer(), $source
			);

			$file = $this->setUrlFile($copy);
		    
		    $message = array(
		    	'status' => 'success',
		    	'fileBlobUrl' => $file['blob'],
		    	'fileUrl' => $file['file'],
		    	'code' => 200
		    );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

		    $message = array(
		    	'status' => 'error',
		    	'code' => $code,
		    	'message' => $error_message
		    );
		}
		
		if ($this->json)
		return $this->sendJsonResponse($message);
		else 
		return $message;
    }
    
    public function renameFile($source, $rename)
	{
		try {

			$this->getProxy()->copyBlob(
				$this->getAccountContainer(), $rename, 
				$this->getAccountContainer(), $source
			);

			$this->getProxy()->deleteBlob($this->getAccountContainer(), $source);

			$file = $this->setUrlFile($rename);
		    
		    $message = array(
		    	'status' => 'success',
		    	'fileBlobUrl' => $file['blob'],
		    	'fileUrl' => $file['file'],
		    	'code' => 200
		    );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

		    $message = array(
		    	'status' => 'error',
		    	'code' => $code,
		    	'message' => $error_message
		    );
		}
		
		if ($this->json)
		return $this->sendJsonResponse($message);
		else 
		return $message;
	}
}
