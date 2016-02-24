<?php

/*
 * This file is part of the RequestLab package.
 *
 * (c) RequestLab <hello@requestlab.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestLab\Estat;

/**
 * Class EstatException
 *
 * @package RequestLab\Estat
 * @author ylcdx <yann@requestlab.fr>
 */
class EstatException extends \Exception
{

    /**
     * Gets the "INVALID ACCESS TOKEN" exception.
     *
     * @param string $error The error message.
     *
     * @return EstatException The "INVALID ACCESS TOKEN" exception.
     */
    public static function invalidAccessToken($error)
    {
        return new self(sprintf('Failed to retrieve access token (%s).', $error));
    }

    /**
     * Gets th "INVALID QUERY" exception
     *
     * @param $error
     * @return EstatException
     */
    public static function invalidQuery($error)
    {
        return new self(sprintf('Invalid query (%S)', $error));
    }

    /**
     * @param $error
     * @return EstatException
     */
    public static function invalidParameter($error)
    {
        return new self(sprintf('An error occured when querying the Estat service (%s is required).', $error));
    }

    /**
     * @param $error
     * @return EstatException
     */
    public static function invalidResponse($error)
    {
        return new self(sprintf('Invalid response (%s).', $error));
    }

}