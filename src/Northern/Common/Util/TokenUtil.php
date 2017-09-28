<?php
/*!
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Portions copyrighted by Kohana team.
 */

namespace Northern\Common\Util;

/**
 * Token util to generate application specific tokens.
 */
class TokenUtil
{
    /**
     * This method will generate a pseudo random SHA1 that can be used as nonce or
     * other kind of token. To specifiy the length of the token use the length
     * parameter.
     *
     * @param int $length
     */
    public static function getNonce($length = 32)
    {
        do {
            $entropy = openssl_random_pseudo_bytes($length, $strong);
        } while ($strong === false);

        $nonce = sha1($entropy);

        return $nonce;
    }

    /**
     * Generates a random string of a given type and length:
     *
     *    $str = random(); // random 8 character alpha-numeric string
     *
     * The following types are supported:
     *
     *    alnum: Upper and lower case a-z, 0-9 (default)
     *    alpha: Upper and lower case a-z
     *    hexdec: Hexadecimal characters a-f, 0-9
     *    distinct: Uppercase characters and numbers that cannot be confused
     *
     * You can also create a custom type by providing the "pool" of characters
     * as the type.
     *
     * @param   string   a type of pool, or a string of characters to use as the pool
     * @param   integer  length of string to return
     * @return string
     *
     * Note: This method is taken from the Kohana 3 Text class.
     */
    public static function random($type = 'alnum', $length = 8)
    {
        switch ($type) {
        case 'alnum':
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;

        case 'alpha':
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;

        case 'hexdec':
            $pool = '0123456789abcdef';
            break;

        case 'numeric':
            $pool = '0123456789';
            break;

        case 'nozero':
            $pool = '123456789';
            break;

        case 'distinct':
            $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
            break;

        default:
            $pool = (string) $type;
            break;
        }

        // Split the pool into an array of characters
        $pool = str_split($pool, 1);

        // Largest pool key
        $max = count($pool) - 1;

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            // Select a random character from the pool and add it to the string
            $str .= $pool[ mt_rand(0, $max) ];
        }

        // Make sure alnum strings contain at least one letter and one digit
        if ($type === 'alnum' and $length > 1) {
            if (ctype_alpha($str)) {
                // Add a random digit
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
            } elseif (ctype_digit($str)) {
                // Add a random letter
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
            }
        }

        return $str;
    }

    public static function getRandomToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}

