<?php

namespace Arrilot\GoogleRecaptcha;

use Exception;
use LogicException;

class Recaptcha
{
    /**
     * @var $this
     */
    private static $instance = null;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $remoteIp = null;

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * Captcha size.
     *
     * @var string
     */
    protected $size = null;
    
    private function __construct()
    {
    }

    protected function __clone()
    {
    }
    
    /**
     * @return $this
     */
    public static function getInstance() {
        if(is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    
    /**
     * @param $key
     * @return $this
     */
    public function setPublicKey($key)
    {
        $this->publicKey = $key;

        return $this;
    }
    
    /**
     * @param $key
     * @return $this
     */
    public function setSecretKey($key)
    {
        $this->secretKey = $key;
        
        return $this;
    }

    /**
     * Set remote IP address
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Set remote IP address
     *
     * @param string $ip
     * @return $this
     */
    public function setRemoteIp($ip = null)
    {
        $this->remoteIp = is_null($ip) ? $_SERVER['REMOTE_ADDR'] : $ip;

        return $this;
    }

    /**
     * Generate the HTML code block for the captcha
     *
     * @param array $dataAttributes
     * @return string
     */
    public function getHtml($dataAttributes = [])
    {
        $this->ensurePublicKeyIsSet();

        $attributesString = 'data-sitekey="'.$this->publicKey.'"';
        foreach ($dataAttributes as $param => $value) {
            $attributesString .= ' data-'. $param .'="' . $value . '"';
        }

        return '<div class="g-recaptcha" ' . $attributesString .'></div>';
    }

    /**
     * Generate the JS code of the captcha
     *
     * @return string
     */
    public function getScript()
    {
        return '<script src="https://www.google.com/recaptcha/api.js?hl='.$this->language.'"></script>';
    }
    
    /**
     * Checks the code given by the captcha
     *
     * @param null|string $response
     * @return bool
     */
    public function verify($response = null)
    {
        $this->ensureSecretKeyIsSet();

        if (is_null($response)) {
            $response = $_POST['g-recaptcha-response'];
        }

        $params = [
            'secret'    => $this->secretKey,
            'response'  => $response,
            'remoteip'  => $this->remoteIp,
        ];

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params));
        if (empty($response)) {
            return false;
        }

        $json = json_decode($response, true);

        return !empty($json['success']);
    }

    /**
     * Ensures that public key is set
     */
    private function ensurePublicKeyIsSet()
    {
        if (is_null($this->publicKey)) {
            throw new LogicException('Public key is not set');
        }
    }

    /**
     * Ensures that secret is set
     */
    private function ensureSecretKeyIsSet()
    {
        if (is_null($this->secretKey)) {
            throw new LogicException('Secret key is not set');
        }
    }
}
