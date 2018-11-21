<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 08/07/2017
 * Time: 16:02
 */
phpinfo();
var_dump(gd_info());
$testGD = get_extension_funcs("gd"); // Grab function list
if (!$testGD){ echo "GD not even installed."; exit; }
echo"<pre>".print_r($testGD,true)."</pre>";