<?php

require 'lib/Slim/Slim.php';
require 'lib/RedBean/rb.php';

\Slim\Slim::registerAutoloader();

class ResourceNotFoundException extends Exception {};
class ResourceNotReachableException extends Exception {};

//

class SlimRest extends \Slim\Slim {

   var $contentType = 'application/json';
   var $debug = false;

   function requestHandler($type,$uri,$fn) {
      $that = $this;
      return parent::$type($uri,function() use($fn,$that) {
         try {
            $returned = call_user_func_array($fn,func_get_args());
            $that->response()->header('Content-Type',$that->contentType);
            echo ($that->debug) ? print_r($returned,true) : json_encode($returned);
         } catch (ResourceNotFoundException $e) {
            $that->response()->status(404);
         } catch (Exception $e) {
            $that->response()->status(400);
            $that->response()->header("X-Status-Reason",$e->getMessage());
         };
      });
   }
   //
   function get($uri,$fn) { return $this->requestHandler('get',$uri,$fn); }
   function post($uri,$fn) { return $this->requestHandler('post',$uri,$fn); }
   function put($uri,$fn) { return $this->requestHandler('put',$uri,$fn); }
   function delete($uri,$fn) { return $this->requestHandler('delete',$uri,$fn); }
   function head($uri,$fn) { return $this->requestHandler('head',$uri,$fn); }
   function options($uri,$fn) { return $this->requestHandler('options',$uri,$fn); }
   function patch($uri,$fn) { return $this->requestHandler('patch',$uri,$fn); }
   //
   function getInput() {
      $request = $this->request();
      $body = $request->getBody();
      $input = json_decode($body);
      foreach ($input as $key=>&$value) {
         $value = $this->_sanitizeString($value);
      };
      return $input;
   }
   //
   function populateWithInput($obj,$properties) {
      if (is_string($obj)) $obj = R::dispense($obj);
      $input = $this->getInput();
      foreach ($properties as $useProperty) {
         if (isset($input->$useProperty)) $obj->$useProperty = $input->$useProperty;
      };
      return $obj;
   }
   //
   private function _sanitizeString($str) {
      $str = (string)$str;
      $str = trim($str);
      return (strlen($str)==0) ? null : $str;
   }
};