<?php
class CryptDes {
    var $key;
    var $iv;
    function CryptDes($key, $iv){
        $this->key = $key;
        $this->iv = $iv;
    }

    function encrypt($input){
        $size = mcrypt_get_block_size(MCRYPT_3DES,MCRYPT_MODE_CBC); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        $input = $this->pkcs5_pad($input, $size); //如果采用PaddingPKCS7，请更换成PaddingPKCS7方法。
        $key = str_pad($this->key,24,'0'); //3DES加密将8改为24
        $td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
        if( $this->iv == '' )
        {
            $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);//如需转换二进制可改成  bin2hex 转换
        return $data;
    }

    function decrypt($encrypted){
       // echo $encrypted;
        $encrypted = base64_decode($encrypted); //如需转换二进制可改成  bin2hex 转换
        $key = str_pad($this->key,24,'0'); //3DES加密将8改为24
        $td = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_CBC,'');//3DES加密将MCRYPT_DES改为MCRYPT_3DES
        if( $this->iv == '' )
        {
            $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        //echo $td ;
        //echo $encrypted;
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y=$this->pkcs5_unpad($decrypted);
        return $y;
    }

    function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text){
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

    function PaddingPKCS7($data) {
        $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);//3DES加密将MCRYPT_DES改为MCRYPT_3DES
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char),$padding_char);
        return $data;
    }
}
/*
$des = new CryptDes("hyki","daodos");//（秘钥向量，混淆向量）
echo $ret = $des->encrypt("cheyikai?&=650792bfa51339e2e14be0a21945126b45fc39b4 ");//加密字符串
echo $ret = $des->decrypt('UWkpPTBMJgXbsM0qIA29eWUL8zrgyIRDdeSEGVaqbqWwYMTN+wakAGTZiUHecebsQgJO+eyKO54=');//FTvFrwCNEMNjAWK1VkMiJtw9PKY1lS7OUOrQvHndF89isZvl73aiK2DGIPot2RbQsFAR MeaTS4=');//加密字符串
*/
?>