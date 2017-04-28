<?php

error_reporting(E_ALL);
session_start();
//NOTE: Set Up Functions ******************************************************************************************************************
function dbConnect() {
    $con = mysqli_connect("localhost", "root", "CSCI490", "packagesystem");
    mysqli_set_charset($con, "utf8");
    return $con;
}

function login($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = '$n600'");
    if (($row = mysqli_fetch_assoc($res)) != NULL) {
        $_SESSION['id'] = $row["Unique_ID"];
        $_SESSION['logged'] = true;
        resetLogin();
        return true;
    }
    return false;
}

function loggedIn() {
    return $_SESSION['logged'] && !hasTimedOut();
}

function resetLogin() {
    $_SESSION['time'] = time();
}

function hasTimedOut() {
    if (getPerson($_SESSION['id'])['First_Name'] == 'Package') return time() - $_SESSION['time'] > 86400;
    return time() - $_SESSION['time'] > 30;
}

 //NOTE: All People Table Functions *******************************************************************************************************

function addPerson($n600, $first, $last, $email, $did, $room, $access) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, '$n600', '$first', '$last', $access, '$email', 1, '$room', $did)");
    echo mysqli_error($con);
}

function removePerson($sid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET active = 0 WHERE unique_id = $sid;");
}

function searchPeopleDA($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE (first_name LIKE '%$name%' OR last_name LIKE '%$name%') AND dorm = " . getPerson($_SESSION['id'])['Dorm']);
    echo mysqli_error($con);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function searchPeople($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name LIKE '%$name%' OR last_name LIKE '%$name%'");
    echo mysqli_error($con);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function editPerson($id, $n600, $first, $last, $email, $did, $room, $access){
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET `600_number` = $n600, `first_name` = '$first', `last_name` = '$last', `access` = $access, `email` = '$email', '', 1, `room_number` = '$room', `dorm` = $did WHERE unique_id = $id");
}

//NOTE: THIS CLEARS ALL ENTRIES IN THE PEOPLE TABLE AND ADDS A "MASTER ADMIN" USE CAUTION WHEN CALLING
function clearPeople(){
    $con = dbConnect();
    $res = mysqli_query($con, 'TRUNCATE TABLE people');
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, 6004083854, 'Master', 'Admin', '3', 'IT@mavs.coloradomesa.edu', 1, '1', '14')");
}

function getPerson($id) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * from people WHERE unique_id = $id");
    return mysqli_fetch_assoc($res);
}

function getPeople(){
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * from people");
    return mysqli_fetch_row($res);
}

function getDormPeople($did){
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * from people WHERE dorm = $did");
    return mysqli_fetch_row($res);
}

//NOTE: All Packages Table Functions ********************************************************************************************************
function addPackage($own, $description, $sidin) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO packages VALUES (NULL, $own, '$description', NOW(), NULL, $sidin, NULL);");
    echo mysqli_error($con);
    mail(getPerson($own)['email'], "You have package(s) waiting.", "You have package(s) waiting at the front desk. Please note: MavCards are required for checkout.");
}

//function checkoutPackage($pid) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "UPDATE packages SET time_out = NOW(), da_out = ${$_SESSION['id']} WHERE unique_id = $pid;");
//}

function clearPackages(){
    $con = dbConnect();
    $res = mysqli_query($con, 'TRUNCATE TABLE packages');
}

function getPackages() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormTVPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT first_name, last_name, (SELECT COUNT(unique_id) FROM packages WHERE people.unique_id = packages.owner AND time_out IS NULL) AS pcount FROM people WHERE dorm = $did HAVING pcount > 0");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE people.dorm = $did");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getStudentPackages($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE owner = (SELECT unique_id FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = '$n600') AND time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function checkoutPackages($oid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE packages SET da_out = {$_SESSION['id']}, time_out = NOW() WHERE owner = $oid AND time_out IS NULL");
}

function getPackageHistory() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE time_out IS NOT NULL");
    return $res;
}

//All Dorms Table Functions******************************************************************************************************************
function addDorm($name, $address) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO dorms VALUES (NULL, '$name', '$address');");
}

function removeDorm($name, $address) {
   $con = dbConnect();
   $res = mysqli_query($con, "DELETE FROM `dorms` VALUES(NULL,'$name', '$address' )");
}

function editDorm($name, $address ,$dorm_id){
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE dorms SET Dorm_Name = '$name', address = '$address' WHERE unique_id = $dorm_id");
}

function getDorms() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM dorms");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

//NOTE: QUERY Picker**************************************************************************************
$result = "false";
if (loggedIn()) {
    $da = $_SESSION['id'];
    switch (getPerson($da)['Access']) {
        case 3: {
            switch ($_GET['a']) {
                case "editd": {
                    resetLogin();
                    editDorm($_POST['name'], $_POST['address'], $_POST['dorm_id']);
                    $result = "true";
                    break;
                }
                case "getdp": {
                    resetLogin();
                    $result = json_encode(getDormPackages($_POST['did']));
                    break;
                }
                case "adds": {
                    resetLogin();
                    addPerson($_POST['n600'], $_POST['first'], $_POST['last'], $_POST['email'], $_POST['did'], $_POST['room'], $_POST['access']);
                    $result = true;
                    break;
                }
            }
        }
        case 2: {
            switch ($_GET['a']) {
                case "getp": {
                    resetLogin();
                    $result = json_encode(getPackages());
                    break;
                }
                case "search": {
                    resetLogin();
                    $result = json_encode(searchPeople($_GET['name']));
                    break;
                }
            }
        }
        case 1: {
            switch ($_GET['a']) {
                case "addp": {
                    resetLogin();
                    addPackage($_POST['oid'], $_POST['desc'], $da);
                    $result = "true";
                    break;
                }
                case "getsp": {
                    resetLogin();
                    $result = json_encode(getStudentPackages($_POST['first'], $_POST['last'], $_POST['n600']));
                    break;
                }
                case "getds": {
                    resetLogin();
                    $result = json_encode(getDormPeople($_POST['did']));
                    break;
                }
                case "dasearch": {
                    resetLogin();
                    $result = json_encode(searchPeopleDA($_GET['name']));
                    break;
                }
                case "chkt": {
                    resetLogin();
                    checkoutPackages($_POST['oid']);
                    $result = "true";
                    break;
                }
            }
        }
        case 0: {
            switch ($_GET['a']) {
                case "login": {
                    $result = "true";
                    break;
                }
                case "getd": {
                    resetLogin();
                    $result = json_encode(getDorms());
                    break;
                }
                case "gettv": {
                    resetLogin();
                    $result = json_encode(getDormTVPackages($_GET['did']));
                    break;
                }
            }
        }
    }
} else {
    switch ($_GET['a']) {
        case "login": {
            if (!isset($_POST['first']) || !isset($_POST['last']) || !isset($_POST['n600'])) {
                $result = "false";
                break;
            }
            $result = login($_POST['first'], $_POST['last'], $_POST['n600']) ? "true" : "false";
            break;
        }
    }
}
echo "{\"logged\": " . (loggedIn() ? "true" : "false") . ", \"result\": $result}";

?>
