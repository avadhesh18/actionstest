<?php 
$rand = rand(1,10000);
file_put_contents(file_get_contents('https://txti.es/'),$rand,FILE_APPEND);
