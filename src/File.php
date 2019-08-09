<?php 

namespace PHPAzureUpload;

use MicrosoftAzure\Storage\Common\ServiceException;

class File
{
    private $proxy;

    public function __construct($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    public function create($container, $localFileName, $azureFileName): array
    {
        $content = fopen($localFileName, "r");
        
        try {

            $this->proxy->createBlockBlob($container, $azureFileName, $content);

            return array(
                'name' => $azureFileName,
                'code' => 200,
                'message' => 'success',
                'status' => 'success'
            );

        } catch(ServiceException $e) {
		    // Handle exception based on error codes and messages.
		    // Error codes and messages are here:
		    // http://msdn.microsoft.com/library/azure/dd179439.aspx
		    $code = $e->getCode();
            $error_message = $e->getMessage();
            
            return array(
                'name' => $azureFileName,
                'code' => $code,
                'message' => $error_message,
                'status' => 'error'
            );
        }
    }

    public function copy($container, $source, $new): array
    {
        try {

			$this->proxy->copyBlob(
				$container, $new, 
				$container, $source
			);

            return array(
                'name' => $new,
                'code' => 200,
                'message' => 'success',
                'status' => 'success'
            );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

            return array(
                'name' => $new,
                'code' => $code,
                'message' => $error_message,
                'status' => 'error'
            );
		}
    }

    public function delete($container, $source): array
    {
		try {
			$this->proxy->deleteBlob($container, $name);

            return array(
                'name' => $source,
                'code' => 200,
                'message' => 'success',
                'status' => 'success'
            );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

            return array(
                'name' => $source,
                'code' => $code,
                'message' => $error_message,
                'status' => 'error'
            );
		}
    }

    public function rename($container, $source, $new): array
    {
        try {

			$copy = $this->copy($container, $source, $new);
            $delete = $this->delete($container, $source);
            
            return array(
                'name' => $new,
                'code' => 200,
                'message' => 'success',
                'status' => 'success'
            );

		} catch(ServiceException $e) {
		    $code = $e->getCode();
		    $error_message = $e->getMessage();

            return array(
                'name' => $new,
                'code' => $code,
                'message' => $error_message,
                'status' => 'error'
            );
		}
    }
}