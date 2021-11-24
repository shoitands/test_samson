<?php
function invertString(string $b): string
{
    $r = "";
    for ($i = strlen($b); $i > 0; $i--)
    {
        $r .= substr($b, $i - 1, 1);
    }
    return $r;
}
function convertString(string $a, string $b): string
{
    $pos = strpos($a, $b);
    if ($pos !== false) $pos = strpos($a, $b, $pos + strlen($b));
    if ($pos !== false)
    {
        $a = substr($a, 0, $pos).invertString($b).substr($a, $pos + strlen($b));
    }
    return $a;
}
//второй метод, но требуется $b только из символов алфавита, или обрабатывать ее, меняя \ на \\ и тд
function preparePattern(string $b): string
{
    return "/(".preg_quote($b).")/";
}
function convertString2(string $a, string $b): string
{
    $p = preparePattern($b);
    $r = preg_replace_callback($p, function($m) use (&$counter, $b)
    {
        if ($counter++ == 1) return invertString($b);
        else return $b;
    },
    $a);
    return $r;
}

function mySortForKey(array $a, string $b): array
{
    for ($i =0; $i < count($a); $i++)
    {
        if (!isset($a[$i][$b])) throw new Exception("mySortForKey: no key ".$b." on position ".$i);
    }
    usort($a, function($x, $y) use ($b)
    {
        if ($x[$b] == $y[$b]) return 0;
        else return ($x[b] < $y[$b]) ? -1 : 1;
    });
    return $a;
}

$dbServer = "127.0.0.1";
$dbUser = "shoitan";
$dbPass = "psiH1238";
$dbName = "test_samson";
$dbHandler = null;

function dbConnect()
{
    global $dbHandler, $dbServer, $dbUser, $dbPass, $dbName;
    if ($dbHandler) return ;
    $dbHandler = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);
}
function dbDisconnect()
{
    global $dbHandler;
    if (!$dbHandler) return ;
    mysqli_close($dbHandler);
}
$catCache = Array();
function dbCategoryGetByName(string $cat): int
{
    global $catCache, $dbHandler;
    if (!isset($catCache[$cat]))
    {
        if ($result = mysqli_query($dbHandler, "SELECT * FROM a_category WHERE name = '$cat';"))
        //if ($result = mysqli_query($dbHandler, "SELECT * FROM a_category;"))
        {
            if ($obj = $result->fetch_object())
            {
                $catCache[$cat] = $obj->id;
                return $obj->id;
            }
            else return 0;
        }
        else
        {
            throw new Exception("dbCategoryGetByName: не удалось сделать запрос");
            return 0;
        }
    }
    else
    {
        return $catCache[$cat];
    }

}
function dbCategoryGetChildren(int $cid): array
{
    global $dbHandler;
    $r = array();
    $query = "
    select  id, name, code, parent_id 
    from    (select * from a_category
    order by parent_id, id) products_sorted,
    (select @pv := $cid) initialisation
    where   find_in_set(parent_id, @pv)
    and     length(@pv := concat(@pv, ',', id));
    ";
    if (!$cid) $query = "SELECT * FROM a_category;";
    $result = mysqli_query($dbHandler, $query);
    $obj = $result->fetch_object();
    while ($obj = $result->fetch_object())
    {
        print_r($obj);
        $r[$obj->id] = $obj;
    }
    return $r;
}
function dbCategoryCreate(string $code, string $name, int $parent = 0)
{
    global $dbHandler;
    $code = iconv("utf-8", "windows-1251", $code);
    if (!$parent) $parent = "null";
    if ($result = mysqli_query($dbHandler, "INSERT a_category(code, name, parent_id) VALUES('$code', '$name', $parent);"))
    {
        return mysqli_insert_id($dbHandler);
    }
    else
    {
        throw new Exception("dbCategoryCreate: не удалось сделать запрос");
        echo mysqli_error($dbHandler).mysqli_info($dbHandler);
    }
}
function dbProductGetById(int $pid): array
{
    global $dbHandler;
    $query = "SELECT * FROM a_product WHERE id=$pid;";
    $result = mysqli_query($dbHandler, $query);
    $obj = $result->fetch_object();
    return array("id" => $obj->id, "name" => $obj->name,"code" => $obj->code);
}
function dbProductGetGroups(int $pid): array
{
    global $dbHandler;
    $r = array();
    $query = "SELECT * FROM a_category_alias WHERE product_id=$pid;";
    $result = mysqli_query($dbHandler, $query);
    while ($obj = $result->fetch_object())
    {
        $r[] = $obj->category_id;
    }
    return $r;
}
function dbProductGetProperties(int $pid): array
{
    global $dbHandler;
    $r = array();
    $query = "SELECT * FROM a_property WHERE product_id=$pid;";
    $result = mysqli_query($dbHandler, $query);
    while ($obj = $result->fetch_object())
    {
        if ($obj->parent_id)
        {
            $r[$obj->parent_id]["attributes"][$obj->id] = array("name" => $obj->property, "value" => $obj->value);
        }
        else $r[$obj->id] = array("name" => $obj->property, "value" => $obj->value, "attributes" => array());
    }
    return $r;
}
function dbProductGetPrices(int $pid): array
{
    global $dbHandler;
    $r = array();
    $query = "SELECT * FROM a_price WHERE product_id=$pid;";
    $result = mysqli_query($dbHandler, $query);
    while ($obj = $result->fetch_object())
    {
        $r[$obj->price_type] = $obj->price;
    }
    return $r;
}
function dbProductCreate(SimpleXMLElement $node, int $pos = 0)
{
    global $dbHandler;
    $name = null;
    $code = null;
    foreach ($node->attributes() as $tag=>$value)
    {
        switch ($tag)
        {
            case "Код": $code = $value; break;
            case "Название": $name = $value; break;
        }
    }
    if (!$code) throw new ParseError("dbProductCreate: нет кода");
    if (!$name) throw new ParseError("dbProductCreate: нет имени");
    mysqli_query($dbHandler, "INSERT a_product(code, name) VALUES ('$code', '$name');");
    $pid = mysqli_insert_id($dbHandler);
    foreach ($node as $tag=>$element)
    {
        switch ($tag)
        {

            case "Цена":
                $price = $element[0];
                $priceType = $element->attributes()->{'Тип'};
                mysqli_query($dbHandler, "INSERT a_price(product_id, price_type, price) VALUES ($pid, '$priceType', '$price');");
                break;
            case "Свойства":
                $properties = Array();
                foreach ($element as $ptag=>$property)
                {
                    mysqli_query($dbHandler, "INSERT a_property(product_id, property, value) VALUES ($pid, '$ptag', '$property[0]');");
                    $prop_id = mysqli_insert_id($dbHandler);
                    foreach ($property->attributes() as $atag=>$value)
                    {
                        mysqli_query($dbHandler, "INSERT a_property(product_id, property, value, parent_id) VALUES ($pid, '$atag', '$value', $prop_id);");
                    }
                }
                break;
            case "Разделы":
                foreach ($element as $ctag=>$cat)
                {
                    if ($ctag == "Раздел")
                    {
                        $cid = dbCategoryGetByName($cat);
                        if (!$cid) $cid = dbCategoryCreate("", $cat);
                        mysqli_query($dbHandler, "INSERT a_category_alias(product_id, category_id) VALUES ($pid, $cid);");
                        echo mysqli_error($dbHandler).mysqli_info($dbHandler);
                    }
                }
                break;
        }
    }
}
function importXml(string $a)
{
    if (!file_exists(__DIR__."\\".$a))
    {
        throw new Exception("importXml: ".$a." not found");
    }
    global $dbHandler;
    if (!$dbHandler) dbConnect();

    mysqli_query($dbHandler, "DELETE FROM a_category_alias WHERE product_id < 9999;");
    mysqli_query($dbHandler, "DELETE FROM a_price WHERE product_id < 9999;");
    mysqli_query($dbHandler, "DELETE FROM a_property WHERE product_id < 9999;");
    mysqli_query($dbHandler, "DELETE FROM a_category WHERE id < 9999;");
    mysqli_query($dbHandler, "DELETE FROM a_product WHERE id < 9999;");
    $data = file_get_contents(__DIR__."\\".$a);
    $xml  = new SimpleXMLElement($data);
    $pos = 0;
    foreach ($xml as $tag=>$value)
    {
        if ($tag == "Товар")
        {
            $pos++;
            dbProductCreate($value, $pos);
        }
    }
    return true;
}

function exportXml(string $a, string $b = null)
{
    global $dbHandler;
    if (!$dbHandler) dbConnect();
    $list = array();
    if ($b === null)
    {
        $query = "
        SELECT a_category_alias.product_id, a_category_alias.category_id, a_category.name, a_category.code
        FROM a_category_alias
        JOIN a_category
        ON a_category_alias.category_id = a_category.id;
        ";
    }
    else
    {
        $cid = dbCategoryGetByName($b);
        $clist = dbCategoryGetChildren($cid);
        $clist[$cid] = null;
        $clist = implode(",", array_keys($clist));
        $query = "
        SELECT a_category_alias.product_id, a_category_alias.category_id, a_category.name, a_category.code
        FROM a_category_alias
        JOIN a_category
        ON a_category_alias.category_id = a_category.id
        WHERE a_category_alias.category_id IN ($clist)
        ";
    }
    $result = mysqli_query($dbHandler, $query);
    while ($obj = $result->fetch_object())
    {
        if (!isset($list[$obj->product_id])) $list[$obj->product_id] = array();
        $list[$obj->product_id][$obj->category_id] = array("code" => $obj->code, "name" => $obj->name);
    }
    $nl = chr(13).chr(10);
    $xml = '<?xml version="1.0" encoding="windows-1251"?>'.$nl;
    $xml .= '<Товары>'.$nl;
    foreach ($list as $pid=>$groups)
    {
        $product = dbProductGetById($pid);
        $properties = dbProductGetProperties($pid);
        $prices = dbProductGetPrices($pid);
        $xml .= '<Товар Код="'.$product["code"].'" Название="'.$product["name"].'">'.$nl;
        foreach ($prices as $type=>$price) $xml .= '<Цена Тип="'.$type.'">'.$price.'</Цена>'.$nl;
        $xml .= '<Свойства>'.$nl;

        foreach ($properties as $id=>$prop)
        {
            $xml .= '<'.$prop["name"];

            foreach ($prop["attributes"] as $aid => $attr)
            {
                $xml .= ' '.$attr["name"].'="'.$attr["value"].'"';
            }
            $xml .= '>'.$prop["value"].'</'.$prop["name"].'>'.$nl;
        }
        $xml .= '</Свойства>'.$nl;
        $xml .= '</Товар>'.$nl;
    }
    $xml .= '</Товары>'.$nl;
    echo $xml;
    $xml = iconv("utf-8", "windows-1251", $xml);
    file_put_contents(__DIR__."/".$a, $xml);
}
?>