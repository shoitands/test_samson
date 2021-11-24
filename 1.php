<?php
//findSimple
function checkSimple(int $n): boolean
{
    if (function_exists("gmp_prob_prime")) return gmp_prob_prime($n);
    for ($i = 2; $i <= ceil($n/2); $i++)
    {
        if ($n % $i == 0) return false;
    }
    return true;
}
function findSimple(int $a, int $b): array
{
    $ret = Array();
    for ($i = $a; $i <= $b; $i++)
    {
        if (checkSimple($i)) $ret[] = $i;
    }
    return $ret;
}

//createTrapeze
function createTrapeze($a): array
{
    if (count($a) % 3 <> 0) throw new InvalidArgumentException('createTrapeze: длинна массива должна быть кратна 3');
    $l = count($a) / 3;
    $ret = Array();
    for ($i = 0; $i < $l; $i++)
    {
        for ($j=0; $j<3; $j++) if (!is_int($a[$i*3+$j]) OR $a[$i*3+$j] < 0) throw new InvalidArgumentException('createTrapeze: неверные входящие данные в массиве на позиции '.($i*3+$j));
        $ret[] = Array(
            "a" => $a[$i*3],
            "b" => $a[$i*3+1],
            "c" => $a[$i*3+2]
        );
    }
    return $ret;
}

//squareTrapeze
function squareTrapeze(array &$a)
{
    if (count($a) % 3 <> 0) throw new InvalidArgumentException('squareTrapeze: длинна массива должна быть кратна 3');
    foreach ($a as $i=>$t)
    {
        foreach ($t as $j=>$v) if (!is_int($v) OR $v < 0) throw new InvalidArgumentException('squareTrapeze: неверные входящие данные в массиве на позиции a['.$i.']['.$j.']');
        $a[$i]["s"] = $a[$i]["c"] * ($a[$i]["a"] + $a[$i]["b"]) / 2;
    }
}

//getSizeForLimit
function getSizeForLimit(array $a, int $b): array
{
    $max = Array("s"=>-1);
    foreach ($a as $i=>$t)
    {
        if (!is_int($t["s"]) OR $t["s"] < 0) throw new InvalidArgumentException('getSizeForLimit: неверные входящие данные в массиве на позиции a['.$i.'][s]');
        if ($t["s"] <= $b AND $t["s"] > $max["s"]) $max = $t;
    }
    if ($max["s"] == -1)
    {
        //обработка ошибки входных данных
    }
    else return $max;
}

//getMin
function getMin(array $a): float
{
    $min = false;
    foreach ($a as $key=>$value)
    {
        if (!is_numeric($value)) throw new InvalidArgumentException('getMin: неверные входящие данные в массиве на позиции a['.$key.']');
        if ($min === false)
        {
            $min = $value;
            continue ;
        }
        else $min = ($value < $min) ? $value : $min;
    }
    return $min;
}
$x=getMin(array(1,2,3,4,5));
print_r($x);
/*printTrapeze
лучше было использовать <tr class="trapezeSelected"> и вставку на css
.trapezeSelected
{
	backgroundColor: red;
}
или использовать jQuery, пройтись по всем строкам таблицы и отметить нужные
проверку на целые числа не делал, использовал округленные
*/
function printTrapeze($a)
{
    $html = "<table>";
    foreach ($a as $i=>$t)
    {
        if (!is_numeric($t["s"])) throw new InvalidArgumentException('printTrapeze: неверные входящие данные в массиве на позиции a['.$i.'][s]');
        $html = ($t["s"] % 2 == 1) ? $html."<tr style=\"background-color: red;\">" : $html."<tr>";
        foreach ($t as $key=>$value)
        {
            $html .= "<td>".$value."</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

//BaseMath
//добавил проверку для $b в exp2 тк иначе выкинет ошибку
abstract class BaseMath
{
    public function exp1(float $a, float $b, float $c): float
    {
        return $a * pow($b, $c);
    }
    public function exp2(float $a, float $b, float $c):float
    {
        if ($b == 0) throw new InvalidArgumentException('BaseMath: $b не может быть равно 0');
        return pow(($a / $b), $c);
    }
    abstract function getValue(): float;
}

//F1
// в классе BaseMath реализована часть вычислений, буду использовать их
// getValue для удобства чтения расписал в неслколько строк
class F1 extends BaseMath
{
    private $a, $b, $c;
    public function __construct(float $a, float $b, float $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
    function getValue(): float
    {
        $f1 = $this->exp1($this->a, $this->b, $this->c);
        $f2 = $this->exp2($this->a, $this->b, $this->c) % 3;
        $min = min($this->a, $this->b, $this->c);
        return  $f1 + pow($f2, $min);
    }
}
?>