<?php

function dbConnect() {
    return mysqli_connect("localhost", "root", "password", "package_system");
}

function addPerson($n600, $first, $last, $email, $did, $room, $da, $coor, $phone = '') {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO *** VALUES ($n600, ");
}

function addPeople($people) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function addPackage($first, $last, $own, $description, $room, $did, $sidin) {
    $con = dbConnect();
    $datein = time();
    $res = mysqli_query($con, "INSERT INTO packages VALUES (0, $first, $last, $own, $description, $room, $did, $datein, NULL, $sidin, NULL);");
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

//function modifyPerson(...) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "QUERY");
//}

function getStudentPackages($n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function getDormPackages($did) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function getPackages() {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function getPackageHistory() {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function login() {
    $con = dbConnect();
}

$da = 0;
$logged = true;


switch ($_GET['a']) {
    case "addp": {
        if (logged) addPackage($_GET['first'], $_GET['last'], $_GET['own'], $_GET['desc'], $_GET['room'], $_GET['did'], $da);
    }
}
?>