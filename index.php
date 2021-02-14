<?php

require __DIR__ . '/vendor/autoload.php';

$searchTags = new WFTags\SearchTags(WFTags\Enum\Types::CLAN);

$list = ['сцена', 'элез', 'актриса', 'аура', 'интерес', '348934284323'];
$define = [100 => 'busy', 'inactive', 'free', 'hide'];

$j = 1;
foreach ($list as $item)
{
    $x = $define[$searchTags->get($item)] ?? false;
    echo "[#$j] $item ($x)\n";
    $j++;
}