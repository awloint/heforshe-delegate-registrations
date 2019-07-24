<?php
/**
 * This script handles the form processing
 *
 * PHP version 7.2
 *
 * @category Registration
 * @package  Registration
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  GPL https://opensource.org/licenses/gpl-license
 * @link     https://stbensonimoh.com
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// echo json_encode($_POST);
// Pull in the required files
require '../config.php';
require './DB.php';
require './Notify.php';

// Capture the post data coming from the form
$firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES);
$lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES);
$email = $_POST['email'];
$phone = $_POST['full_phone'];
$city = htmlspecialchars($_POST['city'], ENT_QUOTES);
$gender = htmlspecialchars($_POST['gender'], ENT_QUOTES);
$occupation = htmlspecialchars($_POST['occupation'], ENT_QUOTES);
$belongToOrganisation = htmlspecialchars($_POST['belongToOrganisation'], ENT_QUOTES);
$organisation = htmlspecialchars($_POST['organisation'], ENT_QUOTES);
$member = htmlspecialchars($_POST['member'], ENT_QUOTES);

$details = array(
    "firstName" => $firstName,
    "lastName" => $lastName,
    "email" => $email,
    "phone" => $phone,
    "city"  => $city,
    "gender" => $gender,
    "occupation" => $occupation,
    "belongToOrganisation"  =>  $belongToOrganisation,
    "organisation"  =>  $organisation,
    "member" => $member
);

$db = new DB($host, $db, $username, $password);
$notify = new Notify($smstoken);

// First check to see if the user is in the Database
if ($db->userExists($email, "heforshe_delegates")) {
    echo json_encode("user_exists");
} else {
    // Insert the user into the database
    $db->getConnection()->beginTransaction();
    $db->insertUser("heforshe_delegates", $details);
    // Send SMS
    $notify->viaSMS(
        "HeForShe",
        "Dear {$firstName} {$lastName}, welcome to the AWLO HeForShe Africa Summit! You registration and accreditation was successful! Enjoy the event!
        - The AWLO Team",
        $phone
    );
    $db->getConnection()->commit();
    echo json_encode("success");
}