<?php

    abstract class ResponseStatus {
        const OK = 200;
        const CREATED = 201;
        const NO_CONTENT = 204;
        const BAD_REQUEST = 400;
        const UNAUTHORIZED = 401;
        const FORBIDDEN = 403;
        const NOT_FOUND = 404;
        const METHOD_NOT_ALLOWED = 405;
        const IM_A_TEAPOT = 418;
        const INTERNAL_SERVER_ERROR = 500;
        const NOT_IMPLEMENTED = 501;
    }


    /**
     * Checks if multiple keys exist in an array
     * Extracted from https://wpscholar.com/blog/check-multiple-array-keys-exist-php/
     * 
     * @param array $array
     * @param array|string $keys
     *
     * @return bool
     */
    function array_keys_exist( array $array, $keys ) {
        $count = 0;
        if ( ! is_array( $keys ) ) {
            $keys = func_get_args();
            array_shift( $keys );
        }
        foreach ( $keys as $key ) {
            if ( isset( $array[$key] ) || array_key_exists( $key, $array ) ) {
                $count ++;
            }
        }

        return count( $keys ) === $count;
    }

    function api_error($responseStatus, $errorMsg) {
        $response = array('error' => $errorMsg); //['error'] = $errorMsg;
        http_response_code($responseStatus);
        echo json_encode($response);
        die();
    }

?>