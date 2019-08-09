<?php 

namespace PHPAzureUpload;

class Connection
{
    protected $connectionString;
    protected $accountName;
    protected $accountKey;
    protected $endpoint;
    protected $protocol;

    public function __construct()
    {
        $this->endpoint = ".blob.core.windows.net";
        $this->protocol = "http";

        return $this;
    }

    /**
     * Get the value of connectionString
     */ 
    public function getConnectionString()
    {
        return $this->connectionString;
    }

    /**
     * Set the value of connectionString
     *
     * @return  self
     */ 
    public function setConnectionString($connectionString)
    {
        $this->connectionString = $connectionString;

        return $this;
    }

    /**
     * Get the value of accountName
     */ 
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set the value of accountName
     *
     * @return  self
     */ 
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get the value of accountKey
     */ 
    public function getAccountKey()
    {
        return $this->accountKey;
    }

    /**
     * Set the value of accountKey
     *
     * @return  self
     */ 
    public function setAccountKey($accountKey)
    {
        $this->accountKey = $accountKey;

        return $this;
    }

    /**
     * Get the value of endpoint
     */ 
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the value of endpoint
     *
     * @return  self
     */ 
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Get the value of protocol
     */ 
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set the value of protocol
     *
     * @return  self
     */ 
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }
}