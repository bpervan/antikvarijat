<?php 
header('Content-disposition: attachment; filename="izvoz.xml"');
header('Content-type: "text/xml"; charset="utf8"');
readfile('izvoz.xml');
//header('Location: http://localhost/antikvarijat/');
?>