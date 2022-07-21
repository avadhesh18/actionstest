<?php 
$rand = rand(1,10000);
file_put_contents('this.txt',$rand,FILE_APPEND);
