<?php

$dirs = array_filter(glob('*'), 'is_dir');

echo "<h3>Directories</h3><ol>";
foreach ($dirs as $dirname) {
    echo "<li><a href='".$dirname."'>".$dirname."</a></li>";
}
echo "</ol>";

unset($dirs);

?>