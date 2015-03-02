<?php

namespace Diside\ProfileBundle\Helper;

class TokenGenerator {

    public static function generateToken()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

}