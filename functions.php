<?php

error_reporting(E_ERROR);

function dbConnect() {
    $con = mysqli_connect("localhost", "root", "", "PackageSystem");
    mysqli_set_charset($con, "utf8");
    return $con;
}

function addPerson($n600, $first, $last, $email, $did, $room, $da, $coor, $phone = '') {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO people VALUES (DEFAULT, $n600, '$first', '$last', " . ($da + $coor * 2) . ", '$email', '$phone', 1, '$room', $did");
}

function addPeople($people) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function addPackage($first, $last, $own, $description, $room, $did, $sidin) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO packages VALUES (DEFAULT, '$first', '$last', $own, '$description', '$room', $did, NOW(), NULL, $sidin, NULL);");
    echo mysqli_error($con);
}

function addDorm($name, $address) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function removePerson($sid) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function removePeople() {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function removePackage($pid) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function removeDorm($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

//function updatePerson(...) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "QUERY");
//}

function getStudentPackages($oid) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE owner = $oid AND time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getDormPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages WHERE dorm = $did AND time_out IS NULL");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function getPackages() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM packages INNER JOIN people ON people.unique_id = packages.owner WHERE time_out IS NULL");
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

function getDorms() {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM dorms");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function searchPeople($name) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name LIKE '{$name}%' OR last_name LIKE '{$name}%'");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function login($n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function hasTimedOut() {
    return false;
}

$da = 1;
 $logged = true;

if (logged) {
    switch ($_GET['a']) {
        case "addp": {
            addPackage($_GET['first'], $_GET['last'], $_GET['oid'], $_GET['desc'], $_GET['room'], $_GET['did'], $da);
            break;
        }
        case "getp": {
            echo "var pdata = " . json_encode(getPackages()) . ";";
            break;
        }
        case "getsp": {
            echo "var pdata = " . json_encode(getStudentPackages($_GET['oid'])) . ";";
            break;
        }
        case "getdp": {
            echo "var pdata = " . json_encode(getDormPackages($_GET['did'])) . ";";
            break;
        }
        case "getd": {
            echo "var ddata = " . json_encode(getDorms()) . ";";
            break;
        }
        case "search": {
            echo json_encode(searchPeople($_GET['name']));
            break;
        }
    }
}
?>