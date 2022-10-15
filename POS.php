<?php

require('./POS_Client.php');

$terminal = new POS_Client(); echo '<br><br>';
$cart_token = $terminal->init() ; echo '<br><br>';
print_r( $terminal->scan("A", $cart_token) ); echo '<br><br>';
print_r( $terminal->scan("D", $cart_token) ); echo '<br><br>';
print_r( $terminal->total('BCDABEAAA', $cart_token));      echo '<br><br>';
print_r( $terminal->total('CCCCCC', $cart_token));      echo '<br><br>';
print_r( $terminal->total('ABCD', $cart_token));      echo '<br><br>';
print_r( $terminal->total('ABECDE', $cart_token));      echo '<br><br>';

