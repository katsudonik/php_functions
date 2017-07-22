<!doctype html>
<dl>
<?php
$fp = fopen('php://stdin', 'r');
while (!feof($fp)) {
    $line = fgets($fp);
    $values = explode("\t", $line);
    echo "<dt>{$values[0]}</dt><dd>{$values[2]}</dd>";
}
?>
</dl>
