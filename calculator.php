<!DOCTYPE html>
<html>
<head>
<title>Калькулятор</title>
<meta charset="utf-8" />
<style>
    body
    {
        text-align: center;
        background-color: #d0ffe7;
    }
    .button1
    {
        background-color: #ffe890;
        width: 100px;
        height: 50px;
        border-radius: 20px;
        text-align: center;
        font-size: medium;
    }
    .operations
    {
        background-color: #ffe890;
        width: 100px;
        height: 50px;
        border-radius: 20px;
        font-size: 200%;
        text-align: center;
    }
</style>
</head>
<body>

<h3>Введите числа в формате ai+b, где a и b - коэфициенты</h3>
<form method="POST">
    <p>а: <input type="text" name="first" value="<?php echo isset($_GET['first']) ? $_GET['first'] : ''; ?>" /> i + b: <input type="text" name="second" value="<?php echo isset($_GET['second']) ? $_GET['second'] : ''; ?>" /> </p>
    <select name="operation" id="oper-select" class="operations">
        <option value="plus">+</option>
        <option value="minus">-</option>
        <option value="multiplication">*</option>
    </select>
    <p>c: <input type="text" name="third" value="<?php echo isset($_GET['third']) ? $_GET['third'] : ''; ?>" /> i + d: <input type="text" name="fourth" value="<?php echo isset($_GET['fourth']) ? $_GET['fourth'] : ''; ?>" /> </p>
    <br>
    <br>

    <input type="submit" value="Вычесть" class = "button1">
</form>
<br>

<?php

function clean($string)
{
    $string = preg_replace('/[()]/', ' ', $string);
    $str1 = str_replace(['i'], '', $string);
    $array = preg_split('/\s+/', $str1);
    $a = array_fill(0, 6, 0);
    $in = 0;
    for($i = 0; $i < count($array); $i++)
    {
        if(is_numeric($array[$i]))
        {
            $a[$in] = $array[$i];
            $in++;
        }
    }

    return $a;
}

function check($a) {    
    if($a<0)
    {
        return "+ (" . $a.")";
    }
    else{
        return "+ " . $a;
    }
}
function addToHistory($result) {
    $history = isset($_COOKIE['calc_history']) ? json_decode($_COOKIE['calc_history'], true) : [];
    $history[] = $result;
    setcookie('calc_history', json_encode($history));
}

function displayHistory() {
    if (isset($_COOKIE['calc_history'])) {
        echo "<h3>История вычислений:</h3>";
        $history = json_decode($_COOKIE['calc_history'], true);
        foreach ($history as $entry) {
            $numbers = clean($entry);
            if (count($numbers) >= 4) {
                $a = $numbers[0];
                $b = $numbers[1];
                $c = $numbers[2];
                $d = $numbers[3];
                echo "<a href='?first=$a&second=$b&third=$c&fourth=$d'>$entry</a><br>";
            } else {
                echo $entry."<br>";
            }
        }
    } else {
        echo "История вычислений отсутствует.";
    }
}
if (isset($_POST["first"]) && isset($_POST["second"]) && isset($_POST["third"]) && isset($_POST["fourth"]) && isset($_POST["operation"])) {

    $first = $_POST["first"];
    $second = $_POST["second"];
    $third = $_POST["third"];
    $fourth = $_POST["fourth"];
    $oper = $_REQUEST["operation"];

    if (is_numeric($first) && is_numeric($second) && is_numeric($third) && is_numeric($fourth))
    {
        switch ($oper) {
            case "plus":
                $sum1 = $first + $third;
                $sum2 = $second + $fourth;
                $result = "Результат: (" . $first . "i " . check($second) . ") + (" . $third . "i " . check($fourth) . ") = " . $sum1 . "i " . check($sum2);
                break;
            
            case "minus":
                $sum1 = $first - $third;
                $sum2 = $second - $fourth;
                $result = "Результат: (" . $first . "i " . check($second) . ") - (" . $third . "i " . check($fourth) . ") = " . $sum1 . "i " . check($sum2);
                break;
        
            case "multiplication":
                $sum1 = $first * $third - $second * $fourth;
                $sum2 = $first * $fourth + $second * $third;
                $result = "Результат: (" . $first . "i " . check($second) . ") * (" . $third . "i " . check($fourth) . ") = " . $sum1 . "i " . check($sum2);
                break;
        
            default:
                $result = "Неверная операция";
                break;
        }

        addToHistory($result);

        header("Location: " . $_SERVER['PHP_SELF']); 

    } else {
        echo "Пожалуйста, введите корректные числовые значения.";
    }
}

displayHistory();

?> 

</body>
</html>