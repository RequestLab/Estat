<?php

/*
 * This file is part of the RequestLab package.
 *
 * (c) RequestLab <hello@requestlab.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestLab\HttpAdapter;

use Widop\HttpAdapter\CurlHttpAdapter;

/**
 * Class JsonHttpAdapter
 *
 * @package RequestLab\HttpAdapter
 * @author ylcdx <yann@requestlab.fr>
 */
class JsonHttpAdapter extends CurlHttpAdapter
{
    /**
     * {@inheritdoc}
     */
    protected function fixContent($content)
    {
        if (is_array($content)) {
            if (isset($content[0]) && $this->isJson($content[0])) {
                return $content[0];
            }
        }

        return parent::fixContent($content);
    }

    /**
     * @param $string
     * @return bool
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}