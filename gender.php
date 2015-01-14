<?php

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['g'])) {
    if ($_GET['g'] == "M" || $_GET['g'] == "m") {
        $G = "M";
        $t = array();
        $t['error'] = "Male gender identification is a work in progress.";
        $json = json_encode((array) $t, JSON_UNESCAPED_UNICODE);
        echo $json;
        die();
    } else if ($_GET['g'] == "f" || $_GET['g'] == "F") {
        $G = "F";
    } else {
        $t = array();
        $t['error'] = "Wrong value passed, currently we only support 'M' and 'F' values.";
        $json = json_encode((array) $t, JSON_UNESCAPED_UNICODE);
        echo $json;
        die();
    }
} else {
    $t = array();
    $t['error'] = "No gender passed in parameter.";
    $json = json_encode((array) $t, JSON_UNESCAPED_UNICODE);
    echo $json;
    die();
}

class MyDB extends SQLite3 {

    function __construct() {
        $this->open('./TnNames.db');
    }

}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
} else {
    //  echo "Opened database successfully NAME: <br>";
}

$sql = <<<EOF
      Select * from FirstNames ORDER BY RANDOM() LIMIT 1;
EOF;

do {
    $ret = $db->query($sql);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $Name = $row['NAME'];
        $Gender = $row['GENDER'];
    }
} while ($Gender != $G);
$sql = <<<EOF
      Select * from LastNames ORDER BY RANDOM() LIMIT 1;
EOF;


$ret = $db->query($sql);
while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
    $LastName = $row['LASTNAME'];
    $Nbr = $row['NUMBER'];
    $LNgender = $row['GENDER'];
}

$Phone = "+216";

$pre = array("20", "22", "23", "24", "50", "52", "52", "53", "55", "33", "98", "99", "97", "95");
$indPre = rand(0, 13);

$j = 0;
$nb = "";
while ($j < 6) {
    $j++;
    $nb = $nb . rand(0, 9);
}

$Phone = $Phone . $pre[$indPre] . $nb;

while (($Name == "" || $LastName == "") || ($LNgender != $Gender)) {
    $ret = $db->query($sql);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $LastName = $row['LASTNAME'];
        $Nbr = $row['NUMBER'];
        $LNgender = $row['GENDER'];
    }
}


$t = array();

$t['Name'] = $Name;
$t['Lastname'] = $LastName;
$t['Gender'] = $Gender;
$t['Phone'] = $Phone;

$json = json_encode((array) $t, JSON_UNESCAPED_UNICODE);
echo $json;

$db->close();
?>
