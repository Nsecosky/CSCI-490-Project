<?php

function dbConnect() {
    return mysqli_connect("host", "user", "password", "database");
}

function addPerson($n600, $first, $last, $email, $did, $room, $da, $coor, $phone = '') {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO *** VALUES ($n600, ");
}

function addPeople($people) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

function addPackage($first, $last, $description, $did, $room, $datein, $dateout, $sidin, $sidout) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
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

function modifyPerson(...) {
    $con = dbConnect();
    $res = mysqli_query($con, "QUERY");
}

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


switch ($_GET['a']) {
    
}
?>