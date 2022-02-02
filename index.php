<?php


// Display all errors 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/./vendor/autoload.php';

// require_once('./config/config.php');
require_once('./src/DatabaseConnector.php');
require_once('./src/LicenceReader.php');

// $filename = './data/o.json';
$filename = './data/ocr.json';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// using factory class to get database connection object
$config = [
    'host' => $_ENV['DB_HOST'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'database' => $_ENV['DB_NAME'],
];

echo "PHP Applicaiton is running 3...";

// $dbConn = DatabaseConnector::create($config);

// $licenceReader = new LicenceReader($filename, $dbConn);

// Task 1 : read and save licence data to database
/*
$resultRead = $licenceReader->readLicence();
$licence = $resultRead['licence'];

$resultSave = $licenceReader->saveLicence($licence);

if ($resultSave['success']) {
    echo 'Saved Licence successfully : ' . $resultSave['licence']['firstname'];
} else {
    'Failed to save Licence';
}
*/

// Task 2 : get list of drivers age limit and year
/*
// drivers with age over 50 and December 2021
$resultDrivers = $licenceReader->getDrivers(50, 12, 2021);
// // drivers with no age limit, for December 2021
// $resultDrivers = $licenceReader->getDrivers(0, 12, 2021);
// // drivers with no age limit, November
// $resultDrivers = $licenceReader->getDrivers(0, 11);

foreach ($resultDrivers as $key=>$driver) {
    echo '<p>';
    echo '> Driver ' . $driver['id'] . ' = £' . $driver['total'];
    echo '</p>';
}
*/