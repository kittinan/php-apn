<?php

/*
 * APNs : Apple Push Notification Service
 * info : https://developer.apple.com/library/ios/documentation/networkinginternet/conceptual/remotenotificationspg/chapters/ApplePushService.html
 * Description : Simple Apple Push Notification class
 */

class APN {
  
  const URIDevelopment = 'ssl://gateway.sandbox.push.apple.com:2195';
  
  const URIProduction = 'ssl://gateway.push.apple.com:2195';

  private $PassPhrase = '';
  private $CertPath = '';
  
  private $isProduction = false;

  function __construct($CertPath, $PassPhrase, $isProduction = false) {
    $this->PassPhrase = $PassPhrase;
    $this->CertPath = $CertPath;
    $this->isProduction = $isProduction;
  }
  
  public function setIsProduction($isProduction) {
    $this->isProduction = $isProduction;
  }

  public function send($token, $message) {

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $this->CertPath);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $this->PassPhrase);
    
    $URIPush = ($this->isProduction == true) ? APN::URIDevelopment : APN::URIDevelopment;

    // Open a connection to the APNS server
    $fp = stream_socket_client($URIPush, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp) {
      fclose($fp);
      return false;
    }
    

    // Create the payload body
    $body['aps'] = array(
        'alert' => $message,
        'sound' => 'default'
    );

    // Encode the payload as JSON
    $payload = json_encode($body);

    // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

    // Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));

    if (!$result) {
      fclose($fp);
      return false;
    }

    // Close the connection to the server
    fclose($fp);
    
    return true;
  }

}
