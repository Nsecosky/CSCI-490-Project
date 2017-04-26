<?php

error_reporting(E_ERROR);
session_start();
//NOTE: Set Up Functions ******************************************************************************************************************
function dbConnect() {
    $con = mysqli_connect("localhost", "root", "CSCI490", "PackageSystem");
    mysqli_set_charset($con, "utf8");
    return $con;
}

function login($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = '$n600'");
    if ($row = mysqli_fetch_assoc($res)) {
        $_SESSION['id'] = $row["unique_id"];
        $_SESSION['logged'] = true;
        $_SESSION['time'] = time();
        return true;
    }
    return false;
}

function loggedIn() {
    return $_SESSION['logged'] && !hasTimedOut();
}

function hasTimedOut() {
    return time() - $_SESSION['time'] > 30;
}

 //NOTE: All People Table Functions *******************************************************************************************************

function addPerson($n600, $first, $last, $email, $did, $room, $access) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, $n600, '$first', '$last', $access, '$email', '', 1, '$room', $did");
}

function removePerson($sid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET active = 0 WHERE unique_id = $sid;");
}

function searchPeople($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name LIKE '%$name%' OR last_name LIKE '%$name%'");
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
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, 6004083854, 'Master', 'Admin', '4', 'IT@mavs.coloradomesa.edu', 1, '1', '14')");
}
//NOTE: This is not necessary reslife wants to just clear people database and start fresh, I know its cringy but its what the client wants.
//function removePeople() {
//    $con = dbConnect();
//    $res = mysqli_query($con, "UPDATE people SET active = 0");
//}

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

function checkoutPackage($pid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE packages SET time_out = NOW(), da_out = ${$_SESSION['id']} WHERE unique_id = $pid;");
}

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
    $res = mysqli_query($con, "SELECT first_name, last_name, (SELECT COUNT(unique_id) FROM packages WHERE people.unique_id = packages.owner AND time_out IS NULL) AS pcount FROM people WHERE dorm = $did HAVING pcount > 1");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE dorm = $did AND time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getStudentPackages($oid) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE owner = $oid AND time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function checkoutPackages($oid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE packages SET time_out = NOW() WHERE own = $oid AND time_out IS NULL");
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

function editDorm(){
    //NOTE: Needs QUERY
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
        case 4: {
            switch ($_GET['a']) {

            }
        }
        case 3: {
            switch ($_GET['a']) {

            }
        }
        case 2: {
            switch ($_GET['a']) {
                case "getp": {
                    $result = json_encode(getPackages());
                    break;
                }
            }
        }
        case 1: {
            switch ($_GET['a']) {
                case "addp": {
                    addPackage($_GET['first'], $_GET['last'], $_GET['oid'], $_GET['desc'], $_GET['room'], $_GET['did'], $da);
                    $result = "true";
                    break;
                }
                case "getsp": {
                    $result = json_encode(getStudentPackages($_GET['oid']));
                    break;
                }
                case "getdp": {
                    $result = json_encode(getDormPackages($_GET['did']));
                    break;
                }
                case "search": {
                    $result = json_encode(searchPeople($_GET['name']));
                    break;
                }
                case "chkt": {
                    checkoutPackages($_GET['oid']);
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
                    $result = json_encode(getDorms());
                    break;
                }
                case "gettv": {
                    $result = json_encode(getDormTVPackages($_GET['did']));
                    break;
                }
            }
        }
    }
} else {
    switch ($_GET['a']) {
        case "login": {
            $result = login($_POST['first'], $_POST['last'], $_POST['n600']) ? "true" : "false";
            break;
        }
    }
}
echo "{\"logged\": " . (loggedIn() ? "true" : "false") . ", \"result\": $result}";

?>
