<?php

require('./POS_Client.php');

$terminal = new POS_Client(); echo '<br><br>';
$cart_token = $terminal->init() ; echo '<br><br>';
print_r( $terminal->scan("B", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("C", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("D", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("A", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("B", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("E", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("A", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("A", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->scan("A", $cart_token) ); echo '<br><br>'; 
print_r( $terminal->total('', $cart_token)); echo '<br><br>'; 
