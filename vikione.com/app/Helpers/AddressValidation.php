<?php
/**
 * CryptoCurrency Address Validation
 *
 * This class retrieve the address is valid or not.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1
 */

namespace App\Helpers;

use IcoHandler;

class AddressValidation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $address;

    /**
     * Create a new address instance.
     *
     * @param  $address
     * @version 1.0.0
     * @since 1.0
     */
    public function __construct($address)
    {
        if (strlen($address) >= 20 && strlen($address) <= 64) {
            $this->address = $address;
        } else {
            return false;
        }
    }

    /**
     * Validate Method
     *
     * @param $type
     * @return boolean
     * @version 1.1
     * @since 1.0
     */
    public function validate($type)
    {
        switch ($type) {
            case 'btc':
                return $this->btcAddress($this->address);
                break;

            case 'ltc':
                return $this->ltcAddress($this->address);
                break;

            case 'eth':
                return $this->ethAddress($this->address);
                break;

            default:
                return $this->anyAddress($this->address);
                break;
        }
    }


    public function nioValidation()
    {
        $ico = new IcoHandler();
        return ( (str_contains(app_key(), $ico->find_the_path($ico->getDomain())) && $ico->cris_cros($ico->getDomain(), app_key(2)) ) && !empty(gws('env_pcode')));
    }

    /**
     * Bitcoin Address Validation
     *
     * @param  $address
     * @version 1.0.0
     * @since 1.0
     * @return boolean
     */
    private function btcAddress($address)
    {
        $decoded = $this->btc_decodeBase58($address);
        if ($decoded !== false) {
            $d1 = hash("sha256", substr($decoded, 0, 21), true);
            $d2 = hash("sha256", $d1, true);
            if (substr_compare($decoded, $d2, 21, 4)) {
                return false;
            }
            return true;
        }
        return false;
    }
    private function btc_decodeBase58($input)
    {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
        $out = array_fill(0, 25, 0);
        for ($i = 0; $i < strlen($input); $i++) {
            if (($p = strpos($alphabet, $input[$i])) === false) {
                return false;
            }
            $c = $p;
            for ($j = 25; $j--;) {
                $c += (int) (58 * $out[$j]);
                $out[$j] = (int) ($c % 256);
                $c /= 256;
                $c = (int) $c;
            }
            if ($c != 0) {
                return false;
            }
        }
        $result = "";
        foreach ($out as $val) {
            $result .= chr($val);
        }
        return $result;
    }

    /**
     * Ethereum Address Validation
     *
     * @param  $address
     * @version 1.0.1
     * @since 1.0
     * @return boolean
     */
    private function ethAddress($address)
    {
        return (preg_match('/^(0x)?[0-9a-f]{40}$/i', $this->address) == 0 ? false : true);
    }

    /**
     * Lite coin Address Validation
     *
     * @param  $address for validation
     * @version 1.0.1
     * @since 1.0
     * @return boolean
     */
    private function ltcAddress($address)
    {
        // the regular expression
        $pattern = '/^[LM3][a-km-zA-HJ-NP-Z1-9]{26,33}$/';
        return (preg_match($pattern, $address) == 0 ? false : true);
    }

    /**
     * Any Address Lenth check
     *
     * @param  $address for validation
     * @version 1.0.0
     * @since 1.1
     * @return boolean
     */
    private function anyAddress($address)
    {
        return ((strlen($address) >= 20 && strlen($address) <= 64) ? true : false);
    }
}
