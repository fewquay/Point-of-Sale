<?php
    header("Content-Type:application/json");
    if(class_exists('Memcache')){
      $memcache = new Memcache;
    }
    $rules = array();
    $item_counts = array();
    $token = '';

    function createRule($key,$price,$price_discounted,$price_discount_at_quantity,$bogo) {
      global $rules, $item_counts;
      
      $rules[$key]['price'] = $price;
      $rules[$key]['price_discounted'] = $price_discounted;
      $rules[$key]['price_discount_at_quantity'] = $price_discount_at_quantity;
      $rules[$key]['bogo'] = $bogo;
      
      $item_counts[$key] = 0;
    }

    createRule('A',2.00,9.00,5,'');
    createRule('B',10.00,0,0,'E');
    createRule('C',1.25,6.00,6,'');
    createRule('D',0.15,0,0,'');
    createRule('E',2.00,0,0,'');  
    $tax = 0.10;

    if (isset($_GET['function']) && $_GET['function']!="") {

      switch ($_GET['function']) {
        case 'total':
            total();
            break;
        case 'scan':
            scan();
            break;
        case 'init':
            init();
            break;            
      }
    }

   function init() {
      $token = uniqid();
      if (isset($memcache)) {
        $memcache->set($token,'');
      }
      $response = array('token' => $token);
      echo json_encode( $response );
    }

   function scan() {
       if (isset($_GET['product']) && $_GET['product']!="") {
         $product = $_GET['product'];
       }
       else {
         exit;
       }
     
      if (isset($memcache)) {
        $currentCart = $memcache->get($_GET['token']);
        $memcache->set($token,$currentCart.$product);
      } 
      $response = array('product' => $product);   
      echo json_encode( $response );
    }

   function total() {
      global $rules, $item_counts, $tax ;
      
      $total = 0 ;
      if (isset($_GET['cart']) && $_GET['cart']!="") {
        $cart = $_GET['cart'];
      }
      elseif (isset($_GET['token']) && $_GET['token']!="") {
        $token = $_GET['token'];
        if (isset($memcache)) {
          $cart = $memcache->get($_GET['token']);
        }
      }
      else {
        exit;
      }
      
      $products = str_split($cart);
      //zero item item counts
      foreach ($rules as $key => $rule) {    
        $item_counts[$key] = 0;
      }
      //get product counts by product name
      foreach ($products as $product) {
        $item_counts[$product]++ ;
      }
      
      //first handle bogo items..remove bogo items so they will not add to price
      //  if count goes negative on any product it means the customer is not taking advantage of the bogo
      foreach ($rules as $key => $rule) {
        $bogo = $rule['bogo'] ;
        
        if ( strlen($bogo) == 0 )
          continue;
          
        $item_counts[$bogo] -= $item_counts[$key] ;  
      }
      //second handle package deals
      foreach ($rules as $key => $rule) {
        $price_discount_at_quantity = $rule['price_discount_at_quantity'] ;
        
        if ( $price_discount_at_quantity <= 0 )
          continue;
        
        $whole_packages = floor( $item_counts[$key] / $price_discount_at_quantity ) ;
        
        if ( $whole_packages <= 0 )
          continue;
          
        $total += $whole_packages * $rule['price_discounted'] ;
        $item_counts[$key] -= $whole_packages * $price_discount_at_quantity ;
      }
      //third count the singles that arent bogo or packages
      foreach ($rules as $key => $rule) {
        $product_count = $item_counts[$key];
        
        if ( $item_counts[$key] <= 0 )
          continue;
          
        $total += $item_counts[$key] * $rule['price'];
      }
      
      $total *= ( 1 + $tax ) ;

      $response = array('total' => $total);   
      echo json_encode($response);
    }


