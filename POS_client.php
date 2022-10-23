<?php

class POS_Client {
  private $token;
  private $base_uri = 'http://44.212.7.198/POS_api.php';
  public $terminal;
  
  public function init() {
    $path = '?function=init';
    $response = $this->get($path);

    return json_decode($response->body)->token;  
  }

  public function scan($product, $cart_token='') {
    $path = '?function=scan&product='.$product.'&token='.$cart_token;
    $response = $this->get($path);

    return json_decode($response->body)->product;  
  }

  public function total($test='', $cart_token='') {
    if (strlen($test) > 0) {
      $path = '?function=total&cart='.$test;
    }
    else {
      $path = '?function=total&token='.$cart_token;
    }
    $response = $this->get($path);

    return json_decode($response->body)->total;  
  }

  public function post($path, $data = array()) {
    return $this->request('POST', $path, $data);
  }
  public function put($path, $data = array()) {
    return $this->request('PUT', $path, $data);
  }
  public function get($path, $data = array()) {
    return $this->request('GET', $path, $data);
  }
  private function request($method, $path, $data) {
    $options = array(
      CURLOPT_URL => $this->base_uri . $path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 60,
    );
    switch ($method) {
      case 'POST':
        $options += array(
          CURLOPT_POST => true,
        );
        break;
      case 'PUT':
        $options += array(
          CURLOPT_CUSTOMREQUEST => 'PUT',
        );
    }
    if (!empty($data)) {
      $body = json_encode($data);
      $options += array(
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($body),
        ),
      );
    }
    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $body = curl_exec($curl);
    $headers = curl_getinfo($curl);
    $error_code = curl_errno($curl);
    $error_msg = curl_error($curl);
    if ($error_code !== 0) {
      $response = array(
        'status'  => $headers['http_code'],
        'error' => $error_msg,
      );
    } else {
      $response = array(
        'body' => $body,
      );
    }
    return (object) $response;
  }  
  
  
}
