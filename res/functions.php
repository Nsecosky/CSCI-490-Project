<?php

error_reporting(E_ERROR);
session_start();
//NOTE: Set Up Functions ******************************************************************************************************************
function dbConnect() {
    $con = mysqli_connect("localhost", "root", "", "packagesystem");
    mysqli_set_charset($con, "utf8");
    return $con;
}

function login($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = SHA2('$n600', 512)");
    $_SESSION['id'] = -1;
    $_SESSION['logged'] = false;
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
    if (getPerson($_SESSION['id'])['Access'] == 0) {
        return time() - $_SESSION['time'] > 86400;
    } else if (getPerson($_SESSION['id'])['Access'] == 1) {
        return time() - $_SESSION['time'] > 60;
    } else {
        return time() - $_SESSION['time'] > 600;
    }
}

 //NOTE: All People Table Functions *******************************************************************************************************

function addPerson($n600, $first, $last, $email, $did, $room, $access) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, SHA2('$n600', 512), '$first', '$last', $access, '$email', 1, '$room', $did)");
    echo mysqli_error($con);
}

function removePerson($sid) {
    $con = dbConnect();
    $res = mysqli_query($con, "DELETE FROM people WHERE unique_id = $sid");
}

function searchPeopleDA($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE (first_name LIKE '%$name%' OR last_name LIKE '%$name%') AND dorm = " . getPerson($_SESSION['id'])['Dorm']);
    echo mysqli_error($con);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function searchPeople($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT *, people.unique_id AS Person_ID FROM people INNER JOIN dorms ON people.Dorm = dorms.Unique_ID WHERE (first_name LIKE '%$name%' OR last_name LIKE '%$name%')");
    echo mysqli_error($con);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function editPerson($id, $n600, $first, $last, $email, $did, $room){
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET `600_number` = SHA2('$n600', 512), `first_name` = '$first', `last_name` = '$last', `email` = '$email', `room_number` = '$room', `dorm` = $did WHERE unique_id = $id");
}

//NOTE: THIS CLEARS ALL ENTRIES IN THE PEOPLE TABLE AND ADDS A "MASTER ADMIN" USE CAUTION WHEN CALLING
function clearPackagesAndPeople(){
    $con = dbConnect();
    mysqli_query($con, 'DELETE FROM packages;');
    mysqli_query($con, 'ALTER TABLE packages AUTO_INCREMENT = 1;');
    mysqli_query($con, 'DELETE FROM people;');
    mysqli_query($con, 'ALTER TABLE people AUTO_INCREMENT = 1;');
    addPerson("6004083854256", 'Master', 'Admin', 'it@mavs.coloradomesa.edu', 1, 100, 3);
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
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

//NOTE: All Packages Table Functions ********************************************************************************************************
function addPackage($own, $description, $sidin) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO packages VALUES (NULL, $own, '$description', NOW(), NULL, $sidin, NULL);");
    echo mysqli_error($con);
    mail(getPerson($own)['email'], "You have package(s) waiting.", "You have package(s) waiting at the front desk. Please note: MavCards are required for checkout.");
}

//function clearPackages(){
//    $con = dbConnect();
//    $res = mysqli_query($con, 'TRUNCATE TABLE packages');
//}

function getPackages() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormTVPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT First_Name, Last_Name, (SELECT COUNT(unique_id) FROM packages WHERE people.unique_id = packages.owner AND time_out IS NULL) AS pcount FROM people WHERE dorm = $did AND people.active = 1 HAVING pcount > 0");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE people.dorm = $did");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getStudentPackages($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE owner = (SELECT unique_id FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = SHA2('$n600', 512)) AND time_out IS NULL");
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

function removeDorm($id) {
   $con = dbConnect();
   $res = mysqli_query($con, "DELETE FROM `dorms` WHERE unique_id = $id");
}

function editDorm($name, $address, $dorm_id){
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE dorms SET Dorm_Name = '$name', address = '$address' WHERE unique_id = $dorm_id");
}

function getDorms() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM dorms");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}


function sanatize($input, $type) {
    switch ($type) {
        case "string": {
            mysqli_real_escape_string($input);
        }
        case "int": {
            filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        }
    }
}

//NOTE: QUERY Picker**************************************************************************************
$result = "false";
if (loggedIn()) {
    $da = $_SESSION['id'];
    switch (getPerson($da)['Access']) {
        case 3: {
            switch ($_GET['a']) {
                case "addd": {
                    sanatize($_POST['name'], "string");
                    sanatize($_POST['address'], "string");
                    
                    resetLogin();
                    addDorm($_POST['name'], $_POST['address']);
                    $result = "true";
                    break;
                }
                case "editd": {
                    sanatize($_POST['name'], "string");
                    sanatize($_POST['address'], "string");
                    sanatize($_POST['id'], "int");
                    
                    resetLogin();
                    editDorm($_POST['name'], $_POST['address'], $_POST['id']);
                    $result = "true";
                    break;
                }
                case "deld": {
                    sanatize($_POST['id'], "int");
                    
                    resetLogin();
                    removeDorm($_POST['id']);
                    $result = "true";
                    break;
                }
                case "getdp": {                    
                    sanatize($_POST['did'], "int");
                    
                    resetLogin();
                    $result = json_encode(getDormPackages($_POST['did']));
                    break;
                }
                case "clearp": {
                    resetLogin();
                    clearPackagesAndPeople();
                    $result = "true";
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
                    sanatize($_POST['name'], "string");
                    
                    resetLogin();
                    $result = json_encode(searchPeople($_GET['name']));
                    break;
                }
                case "adds": {
                    sanatize($_POST['n600'], "string");
                    sanatize($_POST['first'], "string");
                    sanatize($_POST['last'], "string");
                    sanatize($_POST['email'], "string");
                    sanatize($_POST['did'], "int");
                    sanatize($_POST['room'], "string");
                    sanatize($_POST['access'], "int");
                    
                    if (getPerson($da)['Access'] < $_POST['access']) {
                        $result = "false";
                        break;
                    }
                    resetLogin();
                    addPerson($_POST['n600'], $_POST['first'], $_POST['last'], $_POST['email'], $_POST['did'], $_POST['room'], $_POST['access']);
                    $result = "true";
                    break;
                }
                case "editp": {
                    sanatize($_POST['id'], "int");
                    sanatize($_POST['n600'], "string");
                    sanatize($_POST['first'], "string");
                    sanatize($_POST['last'], "string");
                    sanatize($_POST['email'], "string");
                    sanatize($_POST['did'], "int");
                    sanatize($_POST['room'], "string");
                    
                    if (getPerson($da)['Access'] < getPerson($_POST['id'])['Access']) {
                        $result = "false";
                        break;
                    }
                    resetLogin();
                    editPerson($_POST['id'], $_POST['n600'], $_POST['first'], $_POST['last'], $_POST['email'], $_POST['did'], $_POST['room']);
                    $result = "true";
                    break;
                }
                case "delp": {
                    sanatize($_POST['id'], "int");
                    
                    if (getPerson($da)['Access'] < getPerson($_POST['id'])['Access']) {
                        $result = "false";
                        break;
                    }
                    resetLogin();
                    removePerson($_POST['id']);
                    $result = "true";
                    break;
                }
            }
        }
        case 1: {
            switch ($_GET['a']) {
                case "addp": {
                    sanatize($_POST['oid'], "int");
                    sanatize($_POST['desc'], "string");
                    
                    resetLogin();
                    addPackage($_POST['oid'], $_POST['desc'], $da);
                    $result = "true";
                    break;
                }
                case "getsp": {
                    sanatize($_POST['n600'], "string");
                    sanatize($_POST['first'], "string");
                    sanatize($_POST['last'], "string");
                    
                    resetLogin();
                    $result = json_encode(getStudentPackages($_POST['first'], $_POST['last'], $_POST['n600']));
                    break;
                }
                case "getds": {
                    sanatize($_POST['did'], "int");
                    
                    resetLogin();
                    $result = json_encode(getDormPeople($_POST['did']));
                    break;
                }
                case "dasearch": {
                    sanatize($_GET['name'], "string");
                    
                    resetLogin();
                    $result = json_encode(searchPeopleDA($_GET['name']));
                    break;
                }
                case "chkt": {
                    sanatize($_POST['oid'], "int");
                    
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
                    if (!isset($_POST['first']) || !isset($_POST['last']) || !isset($_POST['n600'])) {
                        $result = loggedIn() ? "true" : "false";
                        break;
                    }
                    
                    sanatize($_POST['n600'], "string");
                    sanatize($_POST['first'], "string");
                    sanatize($_POST['last'], "string");
                    
                    $result = (login($_POST['first'], $_POST['last'], $_POST['n600']) ? "true" : "false") . ", \"id\": " . $_SESSION['id'];
                    break;
                }
                case "getd": {
                    resetLogin();
                    $result = json_encode(getDorms());
                    break;
                }
                case "gettv": {
                    sanatize($_POST['did'], "int");
                    
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
            
            sanatize($_POST['n600'], "string");
            sanatize($_POST['first'], "string");
            sanatize($_POST['last'], "string");
                    
            $result = (login($_POST['first'], $_POST['last'], $_POST['n600']) ? "true" : "false") . ", \"id\": " . $_SESSION['id'];
            break;
        }
    }
}
echo "{\"logged\": " . (loggedIn() ? "true" : "false") . ", \"result\": $result}";

?>
