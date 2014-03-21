<?php
namespace classes;
class HashPass
{    
    private $rounds, $randomState;
    function __construct($rounds = 12) 
    {        
        $this->rounds = $rounds;
    }    
    public function Hash($input) 
    {
        $hash = crypt($input, $this->GetSalt());
        return (strlen($hash) > 13) ? $hash : false;
    }

    public function Verify($input, $existingHash) 
    {
        $hash = crypt($input, $existingHash);
        return $hash === $existingHash;
    }

    private function GetSalt() 
    {
        $salt = sprintf('$2a$%02d$', $this->rounds);
        $bytes = $this->GetRandomBytes(16);
        $salt .= $this->EncodeBytes($bytes);
        return $salt;
    }
    
    private function GetRandomBytes($count) 
    {
        $bytes = '';
        if (function_exists('openssl_random_pseudo_bytes') &&
                (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'))  // OpenSSL slow on Win
            $bytes = openssl_random_pseudo_bytes($count);

        if ($bytes === '' && is_readable('/dev/urandom') && ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) 
        {            
            $bytes = fread($hRand, $count);
            fclose($hRand);
        }

        if (strlen($bytes) < $count) 
        {
            $bytes = '';
            if ($this->randomState === null) 
            {
                $this->randomState = microtime();
                if (function_exists('getmypid'))
                    $this->randomState .= getmypid();
            }
            for ($i = 0; $i < $count; $i += 16) 
            {
                $this->randomState = md5(microtime() . $this->randomState);
                if (PHP_VERSION >= '5')
                    $bytes .= md5($this->randomState, true);
                else
                    $bytes .= pack('H*', md5($this->randomState));
            }

            $bytes = substr($bytes, 0, $count);
        }

        return $bytes;
    }

    private function EncodeBytes($input) 
    {
        return strtr(rtrim(base64_encode($input), '='), '+', '.');
    }    
}
?>