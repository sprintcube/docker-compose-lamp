<?php
/**
 * Yii2 cURL wrapper
 * With RESTful support.
 *
 * @category  Web-yii2
 * @package   yii2-curl
 * @author    Nils Gajsek <info@linslin.org>
 * @copyright 2013-2017 Nils Gajsek <info@linslin.org>
 * @license   http://opensource.org/licenses/MIT MIT Public
 * @version   1.3.0
 * @link      http://www.linslin.org
 *
 */

namespace linslin\yii2\curl;

use Yii;


/**
 * Class Curl
 * @package linslin\yii2\curl
 */
class Curl
{
    // ################################################ class vars // ################################################

    /**
     * @var string|boolean
     * Holds response data right after sending a request.
     */
    public $response = null;

    /**
     * @var null|integer
     * Error code holder: https://curl.haxx.se/libcurl/c/libcurl-errors.html
     */
    public $errorCode = null;

    /**
     * @var null|string
     * Error text holder: http://php.net/manual/en/function.curl-strerror.php
     */
    public $errorText = null;

    /**
     * @var integer HTTP-Status Code
     * This value will hold HTTP-Status Code. False if request was not successful.
     */
    public $responseCode = null;

    /**
     * @var string|null HTTP Response Charset
     * (taken from Content-type header)
     */
    public $responseCharset = null;

    /**
     * @var int HTTP Response Length
     * (taken from Content-length header, or strlen() of downloaded content)
     */
    public $responseLength = -1;

    /**
     * @var string|null HTTP Response Content Type
     * (taken from Content-type header)
     */
    public $responseType = null;

    /**
     * @var array|null HTTP Response headers
     * Lists response header in an array if CURLOPT_HEADER is set to true.
     */
    public $responseHeaders = null;

    /**
     * @var array HTTP-Status Code
     * Custom options holder
     */
    protected $_options = [];

    /**
     * @var array
     * Hold array of get params to send with the request
     */
    protected $_getParams = [];

    /**
     * @var array
     * Hold array of post params to send with the request
     */
    protected $_postParams = [];

    /**
     * @var resource|null
     * Holds cURL-Handler
     */
    public $curl = null;

    /**
     * @var string
     * hold base URL
     */
    protected $_baseUrl = '';

    /**
     * @var array default curl options
     * Default curl options
     */
    protected $_defaultOptions = [
        CURLOPT_USERAGENT      => 'Yii2-Curl-Agent',
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
    ];



    // ############################################### class methods // ##############################################

    /**
     * Start performing GET-HTTP-Request
     *
     * @param string  $url
     * @param boolean $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($url, $raw = true)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('GET', $raw);
    }



    /**
     * Start performing HEAD-HTTP-Request
     *
     * @param string $url
     *
     * @return mixed
     * @throws \Exception
     */
    public function head($url)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('HEAD');
    }


    /**
     * Start performing POST-HTTP-Request
     *
     * @param string  $url
     * @param boolean $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function post($url, $raw = true)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('POST', $raw);
    }


    /**
     * Start performing PUT-HTTP-Request
     *
     * @param string  $url
     * @param boolean $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function put($url, $raw = true)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('PUT', $raw);
    }


    /**
     * Start performing PATCH-HTTP-Request
     *
     * @param string $url
     * @param bool $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function patch($url, $raw = true)
    {
        $this->_baseUrl = $url;
        $this->setHeaders([
            'X-HTTP-Method-Override' => 'PATCH'
        ]);
        return $this->_httpRequest('PATCH',$raw);
    }


    /**
     * Start performing DELETE-HTTP-Request
     *
     * @param string  $url
     * @param boolean $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete($url, $raw = true)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('DELETE', $raw);
    }

    /**
     * Start performing OPTIONS-HTTP-Request
     *
     * @param string $url
     * @param bool $raw if response body contains JSON and should be decoded
     *
     * @return mixed
     * @throws \Exception
     */
    public function options($url, $raw = true)
    {
        $this->_baseUrl = $url;
        return $this->_httpRequest('OPTIONS', $raw);
    }


    /**
     * Set curl option
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        //set value
        if (array_key_exists($key, $this->_defaultOptions) && $key !== CURLOPT_WRITEFUNCTION) {
            $this->_defaultOptions[$key] = $value;
        } else {
            $this->_options[$key] = $value;
        }

        //return self
        return $this;
    }


    /**
     * Set get params
     *
     * @param array $params
     * @return $this
     */
    public function setGetParams($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->_getParams[$key] = $value;
            }
        }

        //return self
        return $this;
    }


    /**
     * Set get params
     *
     * @param array $params
     * @return $this
     */
    public function setPostParams($params)
    {
        if (is_array($params)) {
            $this->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($params)
            );
        }

        //return self
        return $this;
    }


    /**
     * Set raw post data allows you to post any data format.
     *
     * @param mixed $data
     * @return $this
     */
    public function setRawPostData($data)
    {
        $this->setOption(
            CURLOPT_POSTFIELDS,
            $data
        );

        //return self
        return $this;
    }


    /**
     * Set get params
     *
     * @param string $data
     * @return $this
     */
    public function setRequestBody($data)
    {
        if (is_string($data)) {
            $this->setOption(
                CURLOPT_POSTFIELDS,
                $data
            );
        }

        //return self
        return $this;
    }


    /**
     * Get URL - return URL parsed with given params
     *
     * @return string The full URL with parsed get params
     */
    public function getUrl()
    {
        if (Count($this->_getParams) > 0) {
            return $this->_baseUrl.'?'.http_build_query($this->_getParams);
        } else {
            return $this->_baseUrl;
        }
    }


    /**
     * Set curl options
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->_options = $options + $this->_options;

        return $this;
    }


    /**
     * Set multiple headers for request.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        if (is_array($headers)) {

            //init
            $parsedHeader = [];

            //collect currently set headers
            foreach ($this->getRequestHeaders() as $header => $value) {
                array_push($parsedHeader, $header.':'.$value);
            }

            //parse header into right format key:value
            foreach ($headers as $header => $value) {
                array_push($parsedHeader, $header.':'.$value);
            }

            //set headers
            $this->setOption(
                CURLOPT_HTTPHEADER,
                $parsedHeader
            );
        }

        return $this;
    }


    /**
     * Set a single header for request.
     *
     * @param string $header
     * @param string $value
     *
     * @return $this
     */
    public function setHeader($header, $value)
    {
        //init
        $parsedHeader = [];

        //collect currently set headers
        foreach ($this->getRequestHeaders() as $headerToSet => $valueToSet) {
            array_push($parsedHeader, $headerToSet.':'.$valueToSet);
        }

        //add override new header
        if (strlen($header) > 0) {
            array_push($parsedHeader, $header.':'.$value);
        }

        //set headers
        $this->setOption(
            CURLOPT_HTTPHEADER,
            $parsedHeader
        );

        return $this;
    }


    /**
     * Unset a single header.
     *
     * @param string $header
     *
     * @return $this
     */
    public function unsetHeader($header)
    {
        //init
        $parsedHeader = [];

        //collect currently set headers and filter "unset" header param.
        foreach ($this->getRequestHeaders() as $headerToSet => $valueToSet) {
            if ($header !== $headerToSet) {
                array_push($parsedHeader, $headerToSet.':'.$valueToSet);
            }
        }

        //set headers
        $this->setOption(
            CURLOPT_HTTPHEADER,
            $parsedHeader
        );

        return $this;
    }


    /**
     * Get all request headers as key:value array
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        //Init
        $requestHeaders = $this->getOption(CURLOPT_HTTPHEADER);
        $parsedRequestHeaders = [];

        if (is_array($requestHeaders)) {
            foreach ($requestHeaders as $headerValue) {
                list ($key, $value) = explode(':', $headerValue, 2);
                $parsedRequestHeaders[$key] = $value;
            }
        }

        return $parsedRequestHeaders;
    }


    /**
     * Get specific request header as key:value array
     *
     * @param string $headerKey
     *
     * @return string|null
     */
    public function getRequestHeader($headerKey)
    {
        //Init
        $parsedRequestHeaders = $this->getRequestHeaders();

        return isset($parsedRequestHeaders[$headerKey]) ? $parsedRequestHeaders[$headerKey] : null;
    }


    /**
     * Unset a single curl option
     *
     * @param string $key
     *
     * @return $this
     */
    public function unsetOption($key)
    {
        //reset a single option if its set already
        if (isset($this->_options[$key])) {
            unset($this->_options[$key]);
        }

        return $this;
    }


    /**
     * Unset all curl option, excluding default options.
     *
     * @return $this
     */
    public function unsetOptions()
    {
        //reset all options
        if (isset($this->_options)) {
            $this->_options = [];
        }

        return $this;
    }


    /**
     * Total reset of options, responses, etc.
     *
     * @return $this
     */
    public function reset()
    {
        if ($this->curl !== null) {
            curl_close($this->curl); //stop curl
        }

        //reset all options
        if (isset($this->_options)) {
            $this->_options = [];
        }

        //reset response & status params
        $this->curl = null;
        $this->errorCode = null;
        $this->response = null;
        $this->responseCode = null;
        $this->responseCharset = null;
        $this->responseLength = -1;
        $this->responseType = null;
        $this->errorText = null;
        $this->_postParams = [];
        $this->_getParams = [];

        return $this;
    }


    /**
     * Return a single option
     *
     * @param string|integer $key
     * @return mixed|boolean
     */
    public function getOption($key)
    {
        //get merged options depends on default and user options
        $mergesOptions = $this->getOptions();

        //return value or false if key is not set.
        return isset($mergesOptions[$key]) ? $mergesOptions[$key] : false;
    }


    /**
     * Return merged curl options and keep keys!
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options + $this->_defaultOptions;
    }


    /**
     * Get curl info according to http://php.net/manual/de/function.curl-getinfo.php
     *
     * @param null $opt
     * @return array|mixed
     */
    public function getInfo($opt = null)
    {
        if ($this->curl !== null && $opt === null) {
            return curl_getinfo($this->curl);
        } elseif ($this->curl !== null && $opt !== null) {
            return curl_getinfo($this->curl, $opt);
        } else {
            return [];
        }
    }


    /**
     * Performs HTTP request
     *
     * @param string  $method
     * @param boolean $raw if response body contains JSON and should be decoded -> helper.
     *
     * @throws \Exception if request failed
     *
     * @return mixed
     */
    protected function _httpRequest($method, $raw = false)
    {
        //set request type and writer function
        $this->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));

        //check if method is head and set no body
        if ($method === 'HEAD') {
            $this->setOption(CURLOPT_NOBODY, true);
            $this->unsetOption(CURLOPT_WRITEFUNCTION);
        }

        //setup error reporting and profiling
        if (defined('YII_DEBUG') && YII_DEBUG) {
            Yii::debug('Start sending cURL-Request: '.$this->getUrl().'\n', __METHOD__);
            Yii::beginProfile($method.' '.$this->_baseUrl.'#'.md5(serialize($this->_getDebugData())), __METHOD__);
        }

        /**
         * proceed curl
         */
        $curlOptions =  $this->getOptions();
        $this->curl = curl_init($this->getUrl());
        curl_setopt_array($this->curl, $curlOptions);
        $response = curl_exec($this->curl);

        //check if curl was successful
        if ($response === false) {

            //set error code
            $this->errorCode = curl_errno($this->curl);
            $this->errorText = curl_strerror($this->errorCode);

            switch ($this->errorCode) {
                // 7, 28 = timeout
                case 7:
                case 28:
                    $this->responseCode = 'timeout';
                    return false;
                    break;

                default:
                    return false;
                    break;
            }
        }

        //extract header / body data if CURLOPT_HEADER are set to true
        if (isset($curlOptions[CURLOPT_HEADER]) && $curlOptions[CURLOPT_HEADER]) {
            $this->response = $this->_extractCurlBody($response);
            $this->responseHeaders = $this->_extractCurlHeaders($response);
        } else {
            $this->response = $response;
        }

        // Extract additional curl params
        $this->_extractAdditionalCurlParameter();

        //end yii debug profile
        if (defined('YII_DEBUG') && YII_DEBUG) {
            Yii::debug('End cURL-Request: '.$this->response, __METHOD__);
            Yii::endProfile($method.' '.$this->getUrl().'#'.md5(serialize($this->_getDebugData())), __METHOD__);
        }

        //check responseCode and return data/status
        if ($this->getOption(CURLOPT_CUSTOMREQUEST) === 'HEAD') {
            return true;
        } else {
            $this->response = $raw ? $this->response : json_decode($this->response, true);
            return $this->response;
        }
    }


    /**
     * Extract additional curl params protected class helper
     */
    protected function _extractAdditionalCurlParameter ()
    {

        /**
         * retrieve response code
         */
        $this->responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);


        /**
         * try extract response type & charset.
         */
        $this->responseType = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);

        if (!is_null($this->responseType) && count(explode(';', $this->responseType)) > 1) {

            list($this->responseType, $possibleCharset) = explode(';', $this->responseType);

            //extract charset
            if (preg_match('~^charset=(.+?)$~', trim($possibleCharset), $matches) && isset($matches[1])) {
                $this->responseCharset = strtolower($matches[1]);
            }
        }


        /**
         * try extract response length
         */
        $this->responseLength = curl_getinfo($this->curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        if((int)$this->responseLength === -1) {
            $this->responseLength = strlen($this->response);
        }
    }


    /**
     * Extract body curl data from response
     *
     * @param string $response
     * @return string
     */
    protected function _extractCurlBody ($response)
    {
        return substr($response, $this->getInfo(CURLINFO_HEADER_SIZE));
    }


    /**
     * Extract header curl data from response
     *
     * @param string $response
     * @return array
     */
    protected function _extractCurlHeaders ($response)
    {
        //Init
        $headers = [];
        $headerText = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(':', $line, 2);
                $headers[$key] = ltrim($value);
            }
        }

        return $headers;
    }


    /**
     * Collects debug data for serialize
     * @return array|bool|mixed
     */
    private function _getDebugData () {

        $data = [];

        if (is_array($this->getOption(CURLOPT_POSTFIELDS))) {
            foreach ($this->getOption(CURLOPT_POSTFIELDS) as $key => $debugItem) {
                if (is_array($debugItem)) {
                    $data[$key] = $debugItem;
                } else if ($debugItem instanceof \CURLFile) {
                    $data[$key] = [
                        'name' => $debugItem->name,
                        'mime' => $debugItem->mime,
                        'postname' => $debugItem->postname,
                    ];
                } // more to come?
            }
        } else {
            $data = $this->getOption(CURLOPT_POSTFIELDS);
        }

        return $data;
    }
}
