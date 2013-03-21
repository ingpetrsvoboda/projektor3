<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author pes2704
 */
class Projektor_App_Request_Request {
    public function jestenevim(){
       if (1 == get_magic_quotes_gpc()) {
                $this->_submitValues = $this->_recursiveFilter('stripslashes', 'get' == $method? $_GET: $_POST);

       }
    }

    /**
     * Recursively apply a filter function
     *
     * @param     string   $filter    filter to apply
     * @param     mixed    $value     submitted values
     * @since     2.0
     * @access    private
     * @return    cleaned values
     */
    private function _recursiveFilter($filter, $value)
    {
        if (is_array($value)) {
            $cleanValues = array();
            foreach ($value as $k => $v) {
                $cleanValues[$k] = $this->_recursiveFilter($filter, $v);
            }
            return $cleanValues;
        } else {
            return call_user_func($filter, $value);
        }
    } // end func _recursiveFilter



    /**
     * Fetch GET data
     *
     * This method returns a key-value array of data sent in the HTTP request query string, or
     * the value of the array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param   string $key
     * @return  array|mixed|null
     */
    public function get( $key = null ) {
        if ( !isset($this->env['slim.request.query_hash']) ) {
            $output = array();
            if ( function_exists('mb_parse_str') && !isset($this->env['slim.tests.ignore_multibyte']) ) {
                mb_parse_str($this->env['QUERY_STRING'], $output);
            } else {
                parse_str($this->env['QUERY_STRING'], $output);
            }
            $this->env['slim.request.query_hash'] = Slim_Http_Util::stripSlashesIfMagicQuotes($output);
        }
        if ( $key ) {
            if ( isset($this->env['slim.request.query_hash'][$key]) ) {
                return $this->env['slim.request.query_hash'][$key];
            } else {
                return null;
            }
        } else {
            return $this->env['slim.request.query_hash'];
        }
    }

    /**
     * Fetch POST data
     *
     * This method returns a key-value array of data sent in the HTTP request body, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param   string $key
     * @return  array|mixed|null
     * @throws  RuntimeException If environment input is not available
     */
    public function post( $key = null ) {
        if ( !isset($this->env['slim.input']) ) {
            throw new RuntimeException('Missing slim.input in environment variables');
        }
        if ( !isset($this->env['slim.request.form_hash']) ) {
            $this->env['slim.request.form_hash'] = array();
            if ( $this->isFormData() ) {
                $output = array();
                if ( function_exists('mb_parse_str') && !isset($this->env['slim.tests.ignore_multibyte']) ) {
                    mb_parse_str($this->env['slim.input'], $output);
                } else {
                    parse_str($this->env['slim.input'], $output);
                }
                $this->env['slim.request.form_hash'] = Slim_Http_Util::stripSlashesIfMagicQuotes($output);
            }
        }
        if ( $key ) {
            if ( isset($this->env['slim.request.form_hash'][$key]) ) {
                return $this->env['slim.request.form_hash'][$key];
            } else {
                return null;
            }
        } else {
            return $this->env['slim.request.form_hash'];
        }
    }
}

?>
