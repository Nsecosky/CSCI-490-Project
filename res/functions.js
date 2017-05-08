var dormsEl = [];
var packagesEl = "";
var searchEl = "";
var studentsEl = "";
var dvm = [];
var pvm = null;
var svm = null;
var stvm = null;

function setDormsData(data) {
    data.unshift({'Unique_ID': -1, 'Dorm_Name': "Please Select a Dorm"});
    for (var i = 0; i < dvm.length; i++) {
        dvm[i].dorms = data;
    }
}

function createVMs() {
    for (var i = 0; i < dormsEl.length; i++) {
        dvm[i] = new Vue({
            el: dormsEl[i],
            data: {
                dorms: []
            }
        });
    }
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

function getDorms() {
    $.ajax("res/functions.php?a=getd", {
        'method': 'GET',
        'dataType': "json",
        'success': function (json) {
            setDormsData(json.result);
        }
    });
    
}

var dfetch = true;
function login(fname, lname, n600) {
    $.ajax("res/functions.php?a=login", {
        'method': 'POST',
        'data': {'first': fname, 'last': lname, 'n600': n600},
        'dataType': "json",
        'success': function (json) {
            if (json.result) {
                if (dfetch && dvm.length > 0) {
                    dfetch = false;
                    getDorms();
                }
                showLogin = false;
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
    $("#600_number").val("");
    $("#600_number2").val("");
    
    $("#first").val("");
    $("#first_name").val("");
    $("#fname").val("");
    $("#fname2").val("");
    
    $("#last").val("");
    $("#last_name").val("");
    $("#lname").val("");
    $("#lname2").val("");
    
    $("#email").val("");
    $("#email2").val("");
    $("#room2").val("");
    $("#room").val("");
    
    $("#dorms").val(-1);
    $("#dorms2").val(-1);
    $("#dorms3").val(-1);
    
    $("#pacdesc").val("");
    
    $("#name").val("");
    $("#name2").val("");
    $("#address").val("");
    $("#address2").val("");
    
    $("#searchText").val("");
    $("#results").val(-1);
}

function addDorm(name, address) {
    $.ajax("res/functions.php?a=addd", {
        'method': 'POST',
        'data': {'name': $(name).val(), 'address': $(address).val()},
        'dataType': "json"
    });
    getDorms();
    clearFields();
}

function editDorm(name, address, id) {
    $.ajax("res/functions.php?a=editd", {
        'method': 'POST',
        'data': {'name': $(name).val(), 'address': $(address).val(), 'id': $(id).val()},
        'dataType': "json"
    });
    getDorms();
    clearFields();
}

function removeDorm(id){
    $.ajax("res/functions.php?a=deld", {
        'method': 'POST',
        'data': {'id': $(id).val()},
        'dataType': "json"
    });
    getDorms();
    clearFields();
}

function addPerson(n600, first, last, email, did, room, access){
    $.ajax("res/functions.php?a=adds", {
        'method': 'POST',
        'data': {'n600': $(n600).val(), 'first': $(first).val(), 'last': $(last).val(), 'email': $(email).val(), 'did': $(did).val(), 'room': $(room).val(), 'access': access},
        'dataType': "json"
    });
    clearFields();
}

function editPerson(id, n600, first, last, email, did, room){
    $.ajax("res/functions.php?a=editp", {
        'method': 'POST',
        'data': {'id': $(id).val(), 'n600': $(n600).val(), 'first': $(first).val(), 'last': $(last).val(), 'email': $(email).val(), 'did': $(did).val(), 'room': $(room).val()},
        'dataType': "json"
    });
    clearFields();
}

function removePerson(id){
    $.ajax("res/functions.php?a=delp", {
        'method': 'POST',
        'data': {'id': $(id).val()},
        'dataType': "json"
    });
    clearFields();
}


function clearPackagesAndPeople() {
    $.ajax("res/functions.php?a=clearp", {
        'dataType': "json",
        'success' : function (response) {
            if (response.result) {
                alert("Database cleared");
            }
        }
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
                response.result.unshift({'Person_ID': -1, 'First_Name': "Select", 'Last_Name': "Person", 'Dorm_Name': "Residence", 'Room_Number': "Number"});
                svm.results = response.result;
            } else {
                svm.results = [];
            }
        },
        "json"
    );
}

//function doDASearch(name) {
//    $.get(
//        "res/functions.php",
//        {"a": "dasearch", "name": name},
//        function (response, status, jqxhr) {
//            if (response.logged && response.result) {
//                svm.results = response.result;
//            } else {
//                svm.results = [];
//            }
//        },
//        "json"
//    );
//}

function getDormStudents(did) {
    $.ajax("res/functions.php?a=getds", {
        'method' : 'POST',
        'data' : {"did": did},
        "success" : function (response) {
            if (response.logged && response.result) {
                stvm.students = response.result;
            } else {
                stvm.students = [];
            }
        },
        "dataType" : "json"
    });
}
