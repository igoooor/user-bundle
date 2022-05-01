<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 06.10.2019
 * Time: 15:02
 */

namespace Igoooor\UserBundle\Utils;

/**
 * Class PasswordGenerator
 */
class PasswordGenerator
{
    /**
     * @param int    $length
     * @param string $specialCharacters
     *
     * @return string
     */
    public function generate(int $length, string $specialCharacters = '~!@#$%^&*(){}[],./?'): string
    {
        $alphanum = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphabet = $alphanum.$specialCharacters;
        $charactersLength = strlen($alphabet);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $alphabet[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
