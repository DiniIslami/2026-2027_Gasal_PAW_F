<!DOCTYPE html>
<html>
<head>
    <title>Hasil Register</title>
</head>
<body>

<h1>Hasil Data Pribadi</h1>

<?php

foreach ($_POST as $field => $value) {
    echo $field . " : " . htmlspecialchars($value) . "<br>";
}

?>

</body>
</html>