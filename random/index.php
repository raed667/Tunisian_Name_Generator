<?php

header('Content-Type: application/json; charset=utf-8');

class MyDB extends SQLite3 {

    function __construct() {
        $this->open('../TnNames.db');
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


$ret = $db->query($sql);
while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
    $Name = $row['NAME'];
    $Gender = $row['GENDER'];
}

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
