<?php

error_reporting(E_ERROR);
session_start();

function dbConnect() {
    $con = mysqli_connect("localhost", "root", "", "PackageSystem");
    mysqli_set_charset($con, "utf8");
    return $con;
}


function addPerson($n600, $first, $last, $email, $did, $room, $da, $coor, $phone = '') {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO people VALUES (NULL, $n600, '$first', '$last', " . ($da + $coor * 2) . ", '$email', '$phone', 1, '$room', $did");
}

//function addPeople($people) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "QUERY");
//}

function addPackage($own, $description, $sidin) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO packages VALUES (NULL, $own, '$description', NOW(), NULL, $sidin, NULL);");
    echo mysqli_error($con);
    $res = mysqli_query($con, "SELECT email from people WHERE unique_id = $own")
    echo mysqli_error($con):
    mail(mysgli_fetch_row($res)['email'], "You have package(s) waiting.", "You have package(s) waiting at the front desk. Please note: MavCards are required for checkout.");
}

function addDorm($name, $address) {
    $con = dbConnect();
    $res = mysqli_query($con, "INSERT INTO dorms VALUES (NULL, '$name', '$address');");
}

function removePerson($sid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET active = 0 WHERE unique_id = $sid;");
}

function removePeople() {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE people SET active = 0");
}

function checkoutPackage($pid) {
    $con = dbConnect();
    $res = mysqli_query($con, "UPDATE packages SET time_out = NOW(), da_out = ${$_SESSION['id']} WHERE unique_id = $pid;");
}

function PackageClear(){
  $con = dbConnect();
  $res = mysqli_query($con, 'TRUNCATE TABLE packages');

}

function StudentClear(){
  $con = dbConnect();
  $res = mysqli_query($con, 'TRUNCATE TABLE people');
  $res = mysqli_query($con, INSERT INTO `people` (`Unique_ID`, `600_Number`, `First_Name`, `Last_Name`, `Access`, `Email`, `Active`, `Room_Number`, `Dorm`) VALUES (NULL, '6004083854', 'Master', 'Admin', '4', 'IT@mavs.coloradomesa.edu', '1', '1', '14'))

}
//function removeDorm($did) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "QUERY");
//}

//function updatePerson(...) {
//    $con = dbConnect();
//    $res = mysqli_query($con, "QUERY");
//}

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

function login($first, $last, $n600) {
    $con = dbConnect();
    $res = mysqli_query($con, "SELECT * FROM people WHERE first_name = '$first' AND last_name = '$last' AND 600_number = '$n600'");
    if ($res) {
        $_SESSION['id'] = mysqli_fetch_assoc($res)["unique_id"];
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

echo "{\"result\": ";
if (loggedIn()) {
    $da = $_SESSION['id'];
    switch ($_GET['a']) {
        case "login": {
            echo "true";
            break;
        }
        case "addp": {
            addPackage($_GET['first'], $_GET['last'], $_GET['oid'], $_GET['desc'], $_GET['room'], $_GET['did'], $da);
            break;
        }
        case "getp": {
            echo json_encode(getPackages());
            break;
        }
        case "getsp": {
            echo json_encode(getStudentPackages($_GET['oid']));
            break;
        }
        case "getdp": {
            echo json_encode(getDormPackages($_GET['did']));
            break;
        }
        case "getd": {
            echo json_encode(getDorms());
            break;
        }
        case "search": {
            echo json_encode(searchPeople($_GET['name']));
            break;
        }
    }
} else {
    switch ($_GET['a']) {
        case "login": {
            echo login($_POST['first'], $_POST['last'], $_POST['n600']) ? "true" : "false";
            break;
        }
        case "gettv": {
            echo json_encode(getDormTVPackages($_GET['did']));
            break;
        }
    }
}
echo "}";
?>
