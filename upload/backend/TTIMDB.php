<?php
/*
* @package TorrentTrader IMDB API
* @version v0.1
*/

class TTIMDB extends IMDB
{
    private $_nodes = Array(
        'http://omdbapi.com/?i=%s'
    );

    public function get($uri = null)
    {
        if ((preg_match('#tt(\d+)#', $uri, $m)) && ($_data = $this->_try($m[0])))
        {
            return $_data;
        }

        return ( bool ) false;
    }

    private function _try( $id )
    {
        $i = 0;

        while ( $i < 2 )
        {
            if (($_data = $this->_request($id, $i)))
            {
                break;
            }

            $i++;
        }

        return $_data;
    }

    private function _request($id, $i)
    {
        $ch = curl_init();

        if ( is_resource($ch) )
        {
            curl_setopt($ch, CURLOPT_URL, sprintf($this->_nodes[$i], $id));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'TT API Client v(0.1)');
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, 'TT:TT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $_data = curl_exec($ch);
            curl_close($ch);
        }

        return ( ! empty($_data) ) ? $this->_parse($_data) : false;
    }

    private function _parse($_data)
    {
        if (($_info = json_decode($_data)) && (!isset($_info->Error)))
        {
            return $_info;
        }

        return ( bool ) false;
    }
}