<?php

header('Content-Type: application/json; charset=utf-8');

$gender = "none";

if (isset($_GET['g']) && $_GET['g'] != "") {
    $genderParam = strtoupper($_GET['g']);

    if ($genderParam == "M") {
        $gender = "M";
    } else if ($genderParam == "F") {
        $gender = "F";
    }
}

if ($gender == "M") {
    $outPut = array();
    $outPut['error'] = "Male names generation is a work in progress.";
    $json = json_encode((array) $outPut, JSON_UNESCAPED_UNICODE);
    die($json);
} else {

    $randomPerson = generateRandomPerson($gender);
    $json = json_encode((array) $randomPerson, JSON_UNESCAPED_UNICODE);
    echo $json;
}

/* * ******************************** * */

class MyDB extends SQLite3 {

    function __construct() {
        $this->open('../TnNames.db');
    }

}

/**
 * Generates random Tunisian Phone number
 */
function generateRandomNumber() {
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
    return $Phone;
}

/**
 * Generate random name
 */
function generateRandomPerson($genderParam) {

    $db = new MyDB();
    if (!$db) {
        die($db->lastErrorMsg());
    }

    $sql = <<<EOF
      Select * from FirstNames ORDER BY RANDOM() LIMIT 1;
EOF;

    do {
        $ret = $db->query($sql);
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $name = $row['NAME'];
            $gender = $row['GENDER'];
        }
    } while ($gender != $genderParam && $genderParam != "none");

    $sql = <<<EOF
      Select * from LastNames ORDER BY RANDOM() LIMIT 1;
EOF;

    $ret = $db->query($sql);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $lastName = $row['LASTNAME'];
        $LNgender = $row['GENDER'];
    }

    while (($name == "" || $lastName == "") || ($LNgender != $gender)) {
        $ret = $db->query($sql);
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $lastName = $row['LASTNAME'];
            $LNgender = $row['GENDER'];
        }
    }
    /*
     * Closing the DB
     */
    $db->close();

    $person = array(
        'Name' => $name,
        'Lastname' => $lastName,
        'Gender' => $gender,
        'Phone' => generateRandomNumber()
    );

    return $person;
}

?>
