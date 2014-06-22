<?php

/*
 * CampBX API Library
 * 
 * @author    Brandon Beasley <http://brandonbeasley.com/>
 * @copyright Copyright (C) 2011 Brandon Beasley
 * @license   GNU GENERAL PUBLIC LICENSE (Version 3, 29 June 2007)
 * 
 *          Please consider donating if you use this library:
 *            
 *              1FaWb7BHALawhbUqG7g9Pq3SNc74cGsE5J
 * 
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
 */


class CampBX {
    
    
    //API Configuration
    const API_URL       = 'https://CampBX.com/api/';
    const RESULT_FORMAT = 'array'; //default is 'json'
    const EXT           = '.php';
    
    private $marketMethods    = array('xdepth', 'xticker');
    private $accountMethods   = array('myfunds', 'myorders', 'mymargins');
    private $quickMethods     = array('tradecancel', 'tradeenter');
    private $transferMethods  = array('sendbtc');
    
    public function __construct(){
	global $campbx;
        $this->username = $campbx['username'];
        $this->password = $campbx['password'];
    }
            
    public function __call($method, $params = array()) {
        
        $this->_validateMethod($method);        
                            
        $url = $this->_buildUrl($method);

        if (!in_array($method, $this->marketMethods)){
            $options = $this->_buildParams($method , $params);
        } else {
            $options = NULL;
        }
        
        // @REMOVE Output POST variables
        //pre_print($options, 'POST:' . $method);
                              
        $result = $this->_connect($url, $options);
        
        $result = $this->_formatResults($result);
        
        return $result;
    }
    
    private function _buildParams($method, $params = array()){
        
        if (!in_array($method, $this->marketMethods)) {
            $params[0]['user'] = $this->username;
            $params[0]['pass'] = $this->password;

            foreach ($params[0] as $k => $v) {
                $options[$k] = $v;
            }
        } else {
            $options = NULL;
        }
        
        return $options;
    }
    
        
    private function _buildUrl($method){
                       
        $url = self::API_URL . $method . self::EXT;
        
        return $url;
    }
            
    private function _validateMethod($method){
                           
        if(in_array($method, $this->marketMethods) 
                OR in_array($method, $this->accountMethods) 
                      OR in_array($method, $this->quickMethods)
                              OR in_array($method, $this->transferMethods)){
                        return TRUE; 
        } else {
            die('FAILURE: Unknown Method'); 
        }
    }
    
    private function _formatResults($results){
        
        if(self::RESULT_FORMAT == strtolower('array')){
        $results = json_decode($results, true);
        }
        
        return $results;
    }
        
    private function _connect($url, $params = NULL){
        
        //open connection
        $ch = curl_init();
                        
        //set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER, TRUE);
        
        //add POST fields
        if ($params != NULL){
                        
            //url encode params array before POST
            $postData = http_build_query($params, '', '&');
                        
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
                               
        //curl_setopt($ch,CURLOPT_HTTPHEADER,array());        
        
        //MUST BE REMOVED BEFORE PRODUCTION (USE for SSL)
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTChash; '
            .php_uname('s').'; PHP/'.phpversion().')');
	
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        
        
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        
        //execute CURL connection
        $returnData = curl_exec($ch);
                
        //$code = $this->returnCode($returnData);        
        
        if( $returnData === false)
        {
            die('<br />Connection error:' . curl_error($ch));
        }
        else
        {
            //Log successful CURL connection
        }
        
        //close CURL connection
        curl_close($ch);
        
                        
        return $returnData;
    }
   
}
$campbx = new CampBX();
