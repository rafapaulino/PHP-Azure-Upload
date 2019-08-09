<?php 

namespace PHPAzureUpload;

use WindowsAzure\Common\ServicesBuilder;
use PHPAzureUpload\Connection;
use PHPAzureUpload\Container;
use PHPAzureUpload\File;
use PHPAzureUpload\Output;

class Factory
{
    protected $connection;
    protected $proxy;
    protected $container;
    protected $accountUrl;
    protected $file;

    public function __construct(Connection $connection, $accountUrl)
    {
        $this->connection = $connection;
        $this->accountUrl = $accountUrl;
        $this->proxy = ServicesBuilder::getInstance()->createBlobService($this->connection->getConnectionString());
        $this->file = new File($this->proxy);
    }

    /**
     * Get the value of container
     */ 
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the value of container
     *
     * @return  self
     */ 
    public function setContainer($container)
    {
        $obj = new Container($this->proxy);
        $obj->setNewContainer($container);
        $container = $obj->getNewContainer();

        $this->container = $container;

        return $this;
    }

    public function create($localFileName, $azureFileName)
    {
        $file = $this->file->create($this->container, $localFileName, $azureFileName);
        $output = new Output($this->accountUrl, $file, $this->container, $this->connection);
        return $output->response();
    }

    public function copy($source, $new)
    {
        $file = $this->file->copy($this->container, $source, $new);
        $output = new Output($this->accountUrl, $file, $this->container, $this->connection);
        return $output->response();
    }

    public function delete($source)
    {
        $file = $this->file->delete($this->container, $source);
        $output = new Output($this->accountUrl, $file, $this->container, $this->connection);
        return $output->response();
    }

    public function rename($source, $new)
    {
        $file = $this->file->rename($this->container, $source, $new);
        $output = new Output($this->accountUrl, $file, $this->container, $this->connection);
        return $output->response();
    }
}
