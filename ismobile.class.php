<?php
/**
 * Copyright (c) 2009, Bruno Fernandes Pereira <bruno@porkaria.com.br>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *
 *    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 *    * Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Bruno PorKaria
 * @license BSD License
 * @link http://www.phpmobile.com.br/ismobile
 */

interface IsMobileLogger {
    public function log($msg);
}

class IsMobile {

    private $user_agent;
    private $http_accept;
    private $mobileDevice = false;
    private $mobiles = null;
    private $logger = false;

    public $UserDefineMobiles = array(); // ex: array('android' => 'Google Skynet');

    private static $defaultMobiles = array(
            'mobileexplorer' => 'Mobile Explorer',
            'palmsource'    => 'Palm',
            'palmscape'	    => 'Palmscape',
            'motorola'	   => "Motorola",
            'nokia'	  => "Nokia",
            'palm'	  => "Palm",

            // Apple
            'ipad'	 => "iPad",
            'iphone'	 => "Apple iPhone",
            'ipod'	 => "Apple iPod Touch",

            // Samsung Tabs
            'SPH-P100' => "Samsung SPH-P100 (Galaxy Tab Tablet on Sprint)",
            'GT-P1000'  => "Samsung Tab",

            // Outros
            'sony'       => "Sony Ericsson",
            'ericsson'	  => "Sony Ericsson",
            'blackberry'		=> "BlackBerry",
            'cocoon'			=> "O2 Cocoon",
            'blazer'			=> "Treo",
            'lg'				=> "LG",
            'amoi'				=> "Amoi",
            'xda'				=> "XDA",
            'mda'				=> "MDA",
            'vario'				=> "Vario",
            'htc_tattoo'		=> "HTC Android",
            'samsung'			=> "Samsung",
            'sharp'				=> "Sharp",
            'sie-'				=> "Siemens",
            'alcatel'			=> "Alcatel",
            'benq'				=> "BenQ",
            'ipaq'				=> "HP iPaq",
            'mot-'				=> "Motorola",
            'playstation portable' 	=> "PlayStation Portable",
            'hiptop'			=> "Danger Hiptop",
            'nec-'				=> "NEC",
            'panasonic'			=> "Panasonic",
            'philips'			=> "Philips",
            'sagem'				=> "Sagem",
            'sanyo'				=> "Sanyo",
            'spv'				=> "SPV",
            'zte'				=> "ZTE",
            'sendo'				=> "Sendo",

            // Operating Systems
            'symbian'				=> "Symbian",
            'SymbianOS'				=> "SymbianOS",
            'elaine'				=> "Palm",
            'palm'					=> "Palm",
            'series60'				=> "Symbian S60",
            'windows ce'			=> "Windows CE",
            'android'     => "Google OS",

            // Browsers
            'obigo'					=> "Obigo",
            'netfront'				=> "Netfront Browser",
            'openwave'				=> "Openwave Browser",
            'mobilexplorer'			=> "Mobile Explorer",
            'operamini'				=> "Opera Mini",
            'opera mini'			=> "Opera Mini",

            // Other
            'digital paths'			=> "Digital Paths",
            'avantgo'				=> "AvantGo",
            'xiino'					=> "Xiino",
            'novarra'				=> "Novarra Transcoder",
            'vodafone'				=> "Vodafone",
            'docomo'				=> "NTT DoCoMo",
            'o2'					=> "O2",

            // Fallback
            'mobile'				=> "Generic Mobile",
            'wireless' 				=> "Generic Mobile",
            'j2me'					=> "Generic Mobile",
            'midp'					=> "Generic Mobile",
            'cldc'					=> "Generic Mobile",
            'up.link'				=> "Generic Mobile",
            'up.browser'			=> "Generic Mobile",
            'smartphone'			=> "Generic Mobile",
            'cellphone'				=> "Generic Mobile"
    );

    /**
     * Class Constructor
     *
     * @param $user_agent User agent to be analysed
     * @param $http_accept
     * @return bool
     */
    public function __construct($user_agent = false, $http_accept = false, $logger = false) {

        $this->setUserAgent($user_agent);
        $this->setHttpAccept($http_accept);
        $this->logger = $logger;

        // merging the Code Igniter's list of mobiles with the user defined ones
        if (isset($this->UserDefineMobiles)) {
            $this->mobiles = array_merge(self::$defaultMobiles, $this->UserDefineMobiles);
        } else {
            $this->mobiles = self::$defaultMobiles;
        }

        return true;
    }

    /**
     * Sets the user agent for this class
     *
     * @param $user_agent A string denoting the user agent being which is accessing the page. If it's null, a fallback is used.
     */
    public function setUserAgent($user_agent = null) {
        if (!empty($user_agent)) {
            $this -> user_agent = strtolower($user_agent);
        }
        else // fallback
        {
            $this -> user_agent = $_SERVER["HTTP_USER_AGENT"];
        }

        return true;
    }

    /**
     * Getter of the user agent property
     *
     * @return String A string denoting the user agent being which is accessing the page
     */
    public function getUserAgent() {
        return $this -> user_agent;
    }

    /**
     * @param $http_accept
     */
    public function setHttpAccept($http_accept) {
        if (!empty($http_accept)) {
            $this -> http_accept = $http_accept;
        }
        else {
            $this -> http_accept = $_SERVER["HTTP_ACCEPT"];
        }

        return true;
    }

    /**
     * Returns the contents of the Accept: header from the current request
     * @return String
     */
    public function getHttpAccept() {
        return $this -> http_accept;
    }

    public function setMobileDevice($mobile_device) {
        $this -> mobileDevice = $mobile_device;

        return true;
    }

    /**
     * Returns the value of mobileDevice
     * @return string
     */
    public function getMobileDevice() {
        return $this -> mobileDevice;
    }

    public function checkMobile() {

        if (is_array($this->mobiles)) {
            foreach ($this->mobiles as $key => $val) {

                if (preg_match("/" . $key . "/i",$this->getUserAgent()) == 1) {

                    $this->setMobileDevice($val);

                    if ($this->logger) {
                        $this->logger->log('IsMobile Log: ' . $this->getMobileDevice());
                    }

                    return true;
                }
            }
        }

        if ($this->checkMobileWap()) {

            $this->setMobileDevice("WAP");

            if ($this->logger) {
                $this->logger->log('IsMobile Log: ' . $this->getMobileDevice());
            }

            return true;
        }

        return false;
    }

    /**
     * This list was taken from the CodeIgniter's User_Agent (http://codeigniter.com/)
     * @return <array>
     */
    protected function mobilesArray() {
        return $this -> mobiles;
    }


    /**
     * Checks if the mobiled device is using WAP
     * @return bool
     */
    public function checkMobileWap() {
        //HTTP_X_WAP_PROFILE
        return preg_match("/wap\.|\.wap/i",$this->getHttpAccept());
    }

}
?>
