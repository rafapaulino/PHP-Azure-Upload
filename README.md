# PHP Azure Upload

Simple upload to azure blob:

## Features

- `v2.0.*` Change Library Design.
- `v1.0.*` Library Initial Design and bugs correct.


### Important informations

- You need PHP 7.2 or higher to use this class.
- This is a free project, feel free to use it in your projects, even if they are commercial. You can also contribute tips, new features and fixes.

-----

## Example of use

Install: composer require rafapaulino/php-azure-upload

```php
<?php

require 'vendor/autoload.php';

use PHPAzureUpload\Connection;
use PHPAzureUpload\Factory;

$connection = new Connection;
$connection->setConnectionString("conectionstring")
->setAccountName("accountname")
->setAccountKey("accountkey")
->setProtocol("https");

$factory = new Factory($connection, "http://accounturl");
$factory->setContainer("extranet");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    echo '<pre>';
    $file = $_FILES['userfile']['tmp_name'];
    $name = trim(strtolower($_FILES['userfile']['name']));
    $name = str_replace(" ", "-", $name);

    print_r($_FILES);

    $result = $factory->create($file, $name);

    var_dump($result);
    echo '</pre>';

}
?>
<form enctype="multipart/form-data" action="" method="POST">

    File: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
