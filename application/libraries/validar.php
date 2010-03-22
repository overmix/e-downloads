<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Library Validar
*
* @author DGmike http://dgmike.wordpress.com
*/
class Validar {
    /**
     * Retira todos os caracteres que nao forem numericos de uma string
     *
     * @param string $value
     * @return string
     */
    function _toNumber ($value) {
        return preg_replace('/[^0-9]/','',$value);
    }
    /**
     * Faz a soma dos produtos de um determinado número decomposto, ate o dois.
     * Por exeplo, _soma (15346,5) efetuará a seguinte equaçao:
     * 5*1 + 4*5 + 3*3 + 2*4
     * Esta funçao e primordial para as funções de cpf e cnpj
     *
     * @param integer $value
     * @param integer $start
     * @return integer
     */
    function _soma ($value,$start) {
        for ($soma=0,$i=$start,$j=0;$i!=1;$i--,$j++) $soma+=$i*$value{$j};
        return $soma;
    }
    
    function cep ($uf,$cep) {
        $cep=self::_toNumber($cep);
        $uf=strtoupper ($uf);
        if      ($uf=='SP') $regex = '/^([1][0-9]{3}|[01][0-9]{4})' . '[0-9]{3}$/';
        else if ($uf=='RJ') $regex = '/^[2][0-8][0-9]{3}'           . '[0-9]{3}$/';
        else if ($uf=='MS') $regex = '/^[7][9][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='MG') $regex = '/^[3][0-9]{4}'                . '[0-9]{3}$/';
        else if ($uf=='MT') $regex = '/^[7][8][8][0-9]{2}'          . '[0-9]{3}$/';
        else if ($uf=='AC') $regex = '/^[6][9]{2}[0-9]{2}'          . '[0-9]{3}$/';
        else if ($uf=='AL') $regex = '/^[5][7][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='AM') $regex = '/^[6][9][0-8][0-9]{2}'        . '[0-9]{3}$/';
        else if ($uf=='AP') $regex = '/^[6][89][9][0-9]{2}'         . '[0-9]{3}$/';
        else if ($uf=='BA') $regex = '/^[4][0-8][0-9]{3}'           . '[0-9]{3}$/';
        else if ($uf=='CE') $regex = '/^[6][0-3][0-9]{3}'           . '[0-9]{3}$/';
        else if ($uf=='DF') $regex = '/^[7][0-3][0-6][0-9]{2}'      . '[0-9]{3}$/';
        else if ($uf=='ES') $regex = '/^[2][9][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='GO') $regex = '/^[7][3-6][7-9][0-9]{2}'      . '[0-9]{3}$/';
        else if ($uf=='MA') $regex = '/^[6][5][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='PA') $regex = '/^[6][6-8][0-8][0-9]{2}'      . '[0-9]{3}$/';
        else if ($uf=='PB') $regex = '/^[5][8][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='PE') $regex = '/^[5][0-6][0-9]{2}'           . '[0-9]{3}$/';
        else if ($uf=='PI') $regex = '/^[6][4][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='PR') $regex = '/^[8][0-7][0-9]{3}'           . '[0-9]{3}$/';
        else if ($uf=='RN') $regex = '/^[5][9][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='RO') $regex = '/^[7][8][9][0-9]{2}'          . '[0-9]{3}$/';
        else if ($uf=='RR') $regex = '/^[6][9][3][0-9]{2}'          . '[0-9]{3}$/';
        else if ($uf=='RS') $regex = '/^[9][0-9]{4}'                . '[0-9]{3}$/';
        else if ($uf=='SC') $regex = '/^[8][89][0-9]{3}'            . '[0-9]{3}$/';
        else if ($uf=='SE') $regex = '/^[4][9][0-9]{3}'             . '[0-9]{3}$/';
        else if ($uf=='TO') $regex = '/^[7][7][0-9]{3}'             . '[0-9]{3}$/';
        else return false;
        if(!preg_match($regex,$cep)) return false;
        return true;
    }
    /**
     * Valida um cpf ou cnpj, dependendo da quantidade de caracteres numericos que a string contiver
     * O cpf ou cnpj podem ser passados com os pontos separadores ou barras
     *
     * @param string $value
     * @return array
     */
    function cpfcnpj ($value) {
        $value=self::_toNumber($value);
        if ( in_array( strlen($value), array (10,11) ) ) return self::CPF($value,true);
        if ( in_array( strlen($value), array (14) ) ) return self::CNPJ($value,true);
        return False;
    }
    /**
     * CPF, pode ser passado com separadores
     *
     * @param string $value
     * @param bool $returnArray
     * @return bool|array
     */
    function CPF ($cpf,$returnArray=false) {
        $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
        if (strlen($cpf) != 11 || $cpf == '00000000191' || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')        {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }
    /**
     * CNPJ, pode ser passado com separadores
     *
     * @param string $value
     * @param bool $returnArray
     * @return bool|array
     */
    function CNPJ ($value,$returnArray=false) {
        $value=self::_toNumber($value);
        if ( !in_array( strlen($value), array (14) ) ) return ($returnArray?array('cnpj',false):false);
        if (str_repeat($value{1},strlen($value))==$value) return ($returnArray?array('cnpj',false):false);
        if ( in_array($value,array ('12345678901230','01234567890107'))) return ($returnArray?array('cnpj',false):false);
        $soma=self::_soma($value,5)+self::_soma(substr($value,4),9);
        $soma=$soma;
        $resultado1=($soma==0 || $soma==1)?0:11-$soma;
        if ($resultado1!=$value{12}) return ($returnArray?array('cnpj',false):false);
        $soma=self::_soma($value,6)+self::_soma(substr($value,5),9);
        $soma=$soma;
        $resultado1=($soma==0 || $soma==1)?0:11-$soma;
        return (($resultado1==$value{13})?($returnArray?array('cnpj',true):true):($returnArray?array('cnpj'):false));
    }

}
