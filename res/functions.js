var dormsEl = "";
var packagesEl = "";
var searchEl = "";
var studentsEl = "";
var dvm = null;
var pvm = null;
var svm = null;
var stvm = null;

function createVMs() {
    dvm = new Vue({
        el: dormsEl,
        data: {
            dorms: []
        }
    });
    pvm = new Vue({
        el: packagesEl,
        data: {
            packages: []
        }
    });
    svm = new Vue({
        el: searchEl,
        data: {
            results: []
        }
    });
    stvm = new Vue({
        el: studentsEl,
        data: {
            students: []
        }
    });
}


$(document).ready(createVMs);

//////////////////////////////

var cardrgx = /(?:%E\?;E\?|\%(\d+)\^(\w+), (\w+) (\w)\s*\?;(\d+)=(\d+)\?)\+E\?/;
function readCard(cinfo) {
    info = cardrgx.exec(cinfo);
    if (info != null) {
        if (info[1] != null) {
            $("#600_number").val(info[5]);
            $("#first_name").val(info[3]);
            $("#last_name").val(info[2]);
        } else {
            $("#600_number").val("");
            $("#first_name").val("");
            $("#last_name").val("");
            alert("Error reading card");
        }
    }
}

function cLogin(cinfo) {
    info = cardrgx.exec(cinfo);
    if (info != null) {
        if (info[1] != null) {
            login(info[3], info[2], info[5]);
        } else {
            alert("Error reading card");
        }
    }
}

var dfetch = true;
function login(fname, lname, n600) {
    $.ajax("res/functions.php?a=login", {
        'method': 'POST',
        'data': {'first': fname, 'last': lname, 'n600': n600},
        'dataType': "json",
        'success': function (json) {
            if (json.result) {
                showLogin = false;
                if (dfetch && typeof dvm !== 'undefined') {
                    dfetch = false;
                    $.ajax("res/functions.php?a=getd", {
                        'method': 'GET',
                        'dataType': "json",
                        'success': function (json) {
                            dvm.dorms = json.result;
                        }
                    });
                }
                $("#overlay").hide();
            }
        }
    });
}

function checkLogin() {
    $.get(
        "res/functions.php",
        {"a": "login"},
        function (response, status, jqxhr) {
            if (!response.logged) {
                reLogin();
            }
        },
        "json"
    );
}

setInterval(checkLogin, 5000);

var showLogin = true;
function reLogin() {
    if (!showLogin) {
        showLogin = true;
        $("#overlay").show();
        $("#overlay").focus();
    }
}

var curcard = "";
function silentLogin(event) {
    curcard += String.fromCharCode(event.charCode);
    if (event.keyCode == 13) {
        cLogin(curcard);
        curcard = "";
    }
}

function clearFields() {
    $("#n600").val("");
    $("#first").val("");
    $("#last").val("");
    $("#600_number").val("");
    $("#fname").val("");
    $("#lname").val("");
    $("#email").val("");
}

function editDorm(){

}

function addStudent(access){
  $.ajax("res/function.php?a=addp", {
    'method' : 'POST',
    'data' : { 'n600' : $("#600_number").val(), 'first' : $("#fname").val(), 'last' : $("#lname").val(), 'email' : $("#email").val(), 'did' : $("#dorms").val(), 'room' : $("#dorms").val(), 'access' : access },

    'dataType': "json"
  });
}



function switchToTab(tabnav, tabcont) {
    $('a[name=nav]').removeClass('NavHighlight');
    $(tabnav).addClass('NavHighlight');$('span').hide();
    $(tabcont).show();
}

function doSearch(name) {
    $.get(
        "res/functions.php",
        {"a": "search", "name": name},
        function (response, status, jqxhr) {
            if (response.logged && response.result) {
                svm.results = response.result;
            } else {
                svm.results = [];
            }
        },
        "json"
    );
}
