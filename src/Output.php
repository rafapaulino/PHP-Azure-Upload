<?php 

namespace PHPAzureUpload;

use PHPAzureUpload\Connection;

class Output
{
    private $accountUrl;
    private $output;
    private $fileResponse;
    private $connection;
    private $container;
    private $blobUrl;
    private $fileUrl;

    public function __construct($accountUrl, $fileResponse, $container, Connection $connection)
    {
        $this->accountUrl = $accountUrl;
        $this->fileResponse = $fileResponse;
        $this->container = $container;
        $this->connection = $connection;
        $this->setBlobUrl();
        $this->setFileUrl();
    }

    public function response()
    {
        $this->fileResponse['blob'] = $this->getBlobUrl();
        $this->fileResponse['file'] = $this->getFileUrl();
        return $this->fileResponse;
    }    

    /**
     * Get the value of blobUrl
     */ 
    public function getBlobUrl()
    {
        return $this->blobUrl;
    }

    /**
     * Set the value of blobUrl
     *
     * @return  self
     */ 
    private function setBlobUrl()
    {
        $name = $this->fileResponse['name'];
        $blobUrl = $this->connection->getProtocol()  . '://' . $this->connection->getAccountName() . $this->connection->getEndpoint() . '/' . $this->container . '/' . $name;    
        $this->blobUrl = $blobUrl;

        return $this;
    }

    /**
     * Get the value of fileUrl
     */ 
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * Set the value of fileUrl
     *
     * @return  self
     */ 
    private function setFileUrl()
    {
        $name = $this->fileResponse['name'];
        $fileUrl = $this->accountUrl . '/' . $this->container . '/'. $name;
        $this->fileUrl = $fileUrl;

        return $this;
    }
}