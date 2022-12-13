<?php

namespace Library\Misc;

class Hash {

    public static function unique(): string {
        return bin2hex(random_bytes(32));
    }

}
