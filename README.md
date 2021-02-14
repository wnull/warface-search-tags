## Example 

```php
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
```

## Result
```text
[#1] сцена (busy)
[#2] элез (busy)
[#3] актриса (busy)
[#4] аура (busy)
[#5] интерес (busy)
[#6] 348934284323 (free)
```