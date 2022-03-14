<?php
$file = fopen('input.txt', 'r');
fscanf($file, "%d", $T);
$lines = [];
for ($i = 0; $i < $T; $i++) {
    $line = trim(fgets($file));
    $lines[] = $line;
}
fclose($file);
$output = '';

foreach ($lines as $operation) {
    $operator = preg_replace('/[0-9]+/', '', $operation);
    $first = explode($operator, $operation)[0];
    $second = explode($operator, $operation)[1];

    if ($operator == "+") {
        $result = bcadd($first, $second);
    }

    if ($operator == "-") {
        $result = bcsub($first, $second);
    }
    $maxLength = max(strlen($first), strlen($second) + 1, strlen($result));
    if ($operator == "-" | $operator == "+") {
        $output .= str_repeat(" ", max($maxLength - strlen($first), 0)) . $first . "\n" .
            str_repeat(" ", $maxLength - strlen($second) - 1) . $operator . $second . "\n" .
            str_repeat(" ", ($maxLength - max(strlen($result), strlen($second) + 1))) .
            str_repeat("-", max(strlen($second) + 1, strlen($result))) . "\n" .
            str_repeat(" ", ($maxLength - strlen($result))) . $result . "\n";
    }

    if ($operator == "*") {
        $result = bcmul($first, $second);
        $multiplier = array_reverse(str_split($second));
        $firstMultiplicationLength = '';
        $max = max(strlen($first), strlen($second) + 1, strlen($result), strlen($firstMultiplicationLength));

        $output .= str_repeat(" ", ($max - strlen($first))) . $first . "\n" .
            str_repeat(" ", $max - strlen($second) - 1) . $operator . $second . "\n";

        if (strlen($second) > 1) {
            $firstMultiplicationLength = strlen(bcmul($first, $multiplier[0]));

            $output .=
                str_repeat(" ", $max - max(strlen($second) + 1, $firstMultiplicationLength)) .
                str_repeat("-", max(strlen($second) + 1, $firstMultiplicationLength)) . "\n";

            for ($i = 0; $i < count($multiplier); $i++) {
                $multiplication = bcmul($first, $multiplier[$i]);
                $output .= str_repeat(" ", max(($max - strlen($multiplication) - $i), 0)) . $multiplication . "\n";
            }
            $output .= str_repeat(" ", ($max - strlen($result))) . str_repeat("-", strlen($result)) . "\n";
        } else {
            $output .= str_repeat("-", max(strlen($second) + 1, strlen($result))) . "\n";
        }
        $output .= str_repeat(" ", ($max - strlen($result))) . $result . "\n";
    }
    $output .= "\n";
}

$file = fopen('output.txt', 'w');
fwrite($file, trim($output) . PHP_EOL);
fclose($file);
