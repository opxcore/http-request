<?php

/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Request\Bags;

use OpxCore\String\Str;
use function count;
use function in_array;

/**
 * ServerBag is a container for HTTP headers from the $_SERVER variable.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 * @author Robert Kiss <kepten@gmail.com>
 */
class ServerBag extends ParameterBag
{
    /**
     * Get the HTTP headers.
     *
     * @return  array
     */
    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->parameters as $key => $value) {

            if (Str::startsWith($key, 'HTTP_')) {
                $headers[Str::cutFromStart($key, 5)] = $value;

            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers[$key] = $value;
            }
        }

        // TODO check and refactor

        if ($this->has('PHP_AUTH_USER')) {
            $headers['PHP_AUTH_USER'] = $this->get('PHP_AUTH_USER');
            $headers['PHP_AUTH_PW'] = $this->get('PHP_AUTH_PW', '');

        } else {
            /*
             * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
             * For this workaround to work, add these lines to your .htaccess file:
             * RewriteCond %{HTTP:Authorization} .+
             * RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]
             *
             * A sample .htaccess file:
             * RewriteEngine On
             * RewriteCond %{HTTP:Authorization} .+
             * RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]
             * RewriteCond %{REQUEST_FILENAME} !-f
             * RewriteRule ^(.*)$ app.php [QSA,L]
             */

            $authorizationHeader = null;

            if ($this->has('HTTP_AUTHORIZATION')) {
                $authorizationHeader = $this->get('HTTP_AUTHORIZATION');
            } elseif ($this->has('REDIRECT_HTTP_AUTHORIZATION')) {
                $authorizationHeader = $this->get('REDIRECT_HTTP_AUTHORIZATION');
            }

            if ($authorizationHeader !== null) {

                if (Str::startsWith($authorizationHeader, 'basic ')) {

                    // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                    $exploded = explode(':', base64_decode(Str::cutFromStart($authorizationHeader, 6)), 2);

                    if (count($exploded) === 2) {
                        [$headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']] = $exploded;
                    }

                } else if (empty($this->parameters['PHP_AUTH_DIGEST']) && (Str::startsWith($authorizationHeader, 'digest '))) {
                    // In some circumstances PHP_AUTH_DIGEST needs to be set
                    $headers['PHP_AUTH_DIGEST'] = $authorizationHeader;
                    $this->parameters['PHP_AUTH_DIGEST'] = $authorizationHeader;

                } else if (0 === stripos($authorizationHeader, 'bearer ')) {
                    /*
                     * XXX: Since there is no PHP_AUTH_BEARER in PHP predefined variables,
                     *      I'll just set $headers['AUTHORIZATION'] here.
                     *      https://php.net/reserved.variables.server
                     */
                    $headers['AUTHORIZATION'] = $authorizationHeader;
                }
            }
        }

        if (!isset($headers['AUTHORIZATION'])) {
            // PHP_AUTH_USER/PHP_AUTH_PW
            if (isset($headers['PHP_AUTH_USER'])) {
                $headers['AUTHORIZATION'] = 'Basic ' . base64_encode($headers['PHP_AUTH_USER'] . ':' . $headers['PHP_AUTH_PW']);
            } elseif (isset($headers['PHP_AUTH_DIGEST'])) {
                $headers['AUTHORIZATION'] = $headers['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }
}
