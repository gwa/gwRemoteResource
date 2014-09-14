<?php
namespace Gwa\Remote;

/**
 * @brief Loads a remote resource using curl.
 * @class RemoteResource
 */
class RemoteResource
{
    private $_url;
    private $_errno;
    private $_errmsg;
    private $_content;
    private $_header;
    private $_options;

    /**
     * @brief The constructor.
     * @param string      $url
     */
    public function __construct( $url )
    {
        $this->_url = $url;

        // set standard options
        $this->_options = array(
            CURLOPT_FRESH_CONNECT   => 1,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_0,
            CURLOPT_SSL_VERIFYPEER  => false
        );

        if (!ini_get('open_basedir')) {
            $this->_options[CURLOPT_FOLLOWLOCATION] = 1;
            $this->_options[CURLOPT_MAXREDIRS] = 2;
        }
    }

    /**
     * @brief Fetches the resource.
     * @return bool success?
     */
    public function fetch()
    {
        $ch = curl_init();

        // set options
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        foreach ($this->_options as $opt=>$value) {
            curl_setopt($ch, $opt, $value);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->_content = curl_exec($ch);
        $this->_errno   = curl_errno($ch);
        $this->_errmsg  = curl_error($ch);
        $this->_header  = curl_getinfo($ch);

        curl_close($ch);

        return $this->_errno==0 ? true : false;
    }

    /**
     * @brief Sets a CURL option
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param string $opt
     * @param string $value
     */
    public function setOption( $opt, $value )
    {
        $this->_options[$opt] = $value;
    }

    /**
     * @brief Sets username and password to be passed on fetch.
     * @param string $user
     * @param string $password
     */
    public function setUserPassword( $user, $password )
    {
        $this->setOption(CURLOPT_USERPWD, $user.':'.$password);
    }

    /**
     * @return string
     */
    public function getErrorNumber()
    {
        return $this->_errno;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->_errmsg;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_header;
    }

    /**
     * @return string
     */
    public function getHTTPCode()
    {
        return $this->_header['http_code'];
    }

    /**
     * @brief Returns the content.
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @brief Returns the title of a HTML page.
     * @return string
     */
    public function getTitle()
    {
        $pattern = '/<title>(.*)<\/title>/i';
        if (preg_match($pattern, $this->_content, $match)) {
            return $match[1];
        }
        return '';
    }
}
