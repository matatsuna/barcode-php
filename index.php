<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>1次元バーコードジェネレーター</title>
    <meta name="Keywords" content="1次元バーコードジェネレーター" />
    <meta name="Description" content="1次元バーコードジェネレーター" />
  </head>
  <body>
      <div align=center>
       <font size=5>
        <b>  <p>1次元バーコードジェネレーター</p></b>
    <p>1次元バーコード(JANコード)を生成します。</p>
    <p>商品に書かれてる13桁の番号を入力してください。</p>
    <form name="form1" method="post" action="index.php">
<select name="select">
    <?php
    for($i=1;$i<=7;$i++){
    echo "<option value=\"".$i."\"";
    if (isset($_REQUEST["select"])){
    if ($i==(int)$_REQUEST["select"]) {
        echo " selected ";
    }}
    echo ">x".$i."</option>";
    }
    ?>
</select>
    
<form action="barcode.php" method="POST">
<input type=text name="Num" value="<?php if (isset($_POST["Num"])) echo $_POST["Num"] ?>">
<input type=submit>
</form>
</font>
</div>
<div align=center>
<?php
if (!isset($_POST["Num"]) && !isset($_GET["Num"])){
    exit;
}

if (isset($_POST["Num"])){
    $GetNum=$_POST["Num"];    
    $GetRate=(int)$_REQUEST["select"];    
}
if (isset($_GET["Num"])){
    $GetNum=$_GET["Num"];    
    $GetRate=$_GET["select"];
}
// var_dump($GetNum);
if (!preg_match("/^[0-9]{13}+$/",$GetNum)){
    exit("数字以外の文字が含まれているか文字数が違います。");
}

if ((int)substr($GetNum,12,1)!=substr(ChkDigit($GetNum),strlen(ChkDigit($GetNum))-1,1)) {
    exit("この番号は正しくありません。");
}

$PriFix=substr($GetNum,0,1);
//echo $PriFix;
//echo"<br>";
switch ($PriFix) {
    case '0':
        $PriFix="000000";
        break;
    case '1':
        $PriFix="001011";
        break;
    case '2':
        $PriFix="001101";
        break;
     case '3':
        $PriFix="001110";
        break;
     case '4':
        $PriFix="010011";
        break;
    case '5':
        $PriFix="011001";
        break;
    case '6':
        $PriFix="011100";
        break;
    case '7':
        $PriFix="010101";
        break;
    case '8':
        $PriFix="010110";
        break;
    case '9':
        $PriFix="011010";
        break;
    default:
        # code...
        break;
}
// echo $PriFix;
//echo "<br>";
$LeftNum=substr($GetNum,1,6);
$RightNum=substr($GetNum,7,6);
// echo $LeftNum;
// echo"<br>";
// echo $RightNum;

$LeftBit="";
for ($i=0; $i < 6; $i++) { 
    if (substr($PriFix,$i,1)=="0") {
        $LeftBit=$LeftBit.LeftOdd(substr($LeftNum,$i,1));
    }else {
        $LeftBit=$LeftBit.LeftEven(substr($LeftNum,$i,1));
    }
//echo "<br>";
//echo $LeftBit;
}
$RightBit="";
for ($i=0; $i < 6; $i++) { 
   $RightBit=$RightBit.RightEven(substr($RightNum,$i,1));
   //echo "<br>";
   //echo $RightBit;
}

$Code="101".$LeftBit."01010".$RightBit."101";
//echo "<br>";
//echo $Code;

$image = imagecreate( 105*$GetRate, 30*$GetRate );
$background = imagecolorallocate($image, 255, 255, 255);
$color = imagecolorallocate($image,0,0,0);
for($i=0;$i<strlen($Code);$i++)
{
    if (substr($Code,$i,1)==1) {
        for ($j=0; $j <$GetRate ; $j++) { 
            imageline($image,($i+5)*$GetRate+$j,0,($i+5)*$GetRate+$j,30*$GetRate,$color);
        }
    }
}

ob_start();
imagepng( $image );
$content = base64_encode(ob_get_contents());
ob_end_clean();
imagedestroy($image);



function LeftOdd($Num){//奇数
    switch ($Num) {
        case '0':
            return "0001101";
            break;
        
        case '1':
            return "0011001";
            break;
        case '2':
            return "0010011";
            break;
        case '3':
            return "0111101";
            break;
        case '4':
            return "0100011";
            break;
        case '5':
            return "0110001";
            break;
        case '6':
            return "0101111";
            break;
        case '7':
            return "0111011";
            break;
        case '8':
            return "0110111";
            break;
        case '9':
            return "0001011";
            break;
        default:
            # code...
            break;
    }
    return "ERR";
}
function LeftEven($Num){//偶数
    switch ($Num) {
        case '0':
            return "0100111";
            break;
        
        case '1':
            return "0110011";
            break;
        case '2':
            return "0011011";
            break;
        case '3':
            return "0100001";
            break;
        case '4':
            return "0011101";
            break;
        case '5':
            return "0111001";
            break;
        case '6':
            return "0000101";
            break;
        case '7':
            return "0010001";
            break;
        case '8':
            return "0001001";
            break;
        case '9':
            return "0010111";
            break;
        default:
            # code...
            break;
    }
    return "ERR";
}
function RightEven($Num)
{
    switch ($Num) {
        case '0':
            return "1110010";
            break;
        case '1':
            return "1100110";
            break;
        case '2':
            return "1101100";
            break;
        case '3':
            return "1000010";
            break;
        case '4':
            return "1011100";
            break;
        case '5':
            return "1001110";
            break;
        case '6':
            return "1010000";
            break;
        case '7':
            return "1000100";
            break;
        case '8':
            return "1001000";
            break;
        case '9':
            return "1110100";
            break;
        default:
            # code...
            break;
    }
    return "ERR";
}
function ChkDigit($GetNum)
{
    $EvenNum=0;
    for ($i=1; $i <=11 ; $i+=2) {
        $EvenNum=$EvenNum+(int)substr($GetNum,$i,1);
       }
    $EvenNum=$EvenNum*3;
    
    $OddNum=0;
    for ($i=0; $i <=10 ; $i+=2) {
        $OddNum=$OddNum+(int)substr($GetNum,$i,1);
       }
       return 10-(int)substr($OddNum+$EvenNum,strlen($OddNum+$EvenNum)-1,1);
}
?>
<br>
<img src="data:image/jpeg;base64,<?php echo $content;?>" alt="sample" />
<br>
<?php
echo "<font size=\"".$GetRate."\">";
echo $GetNum;
echo "</font>";
?>

<br>
<br>

<script src="http://i.yimg.jp/images/yjdn/js/bakusoku-jsonp-v1-min.js"
  data-url="http://shopping.yahooapis.jp/ShoppingWebService/V1/json/itemSearch"
  data-p-appid="dj0zaiZpPWZ4TUxmR3prSEY1byZzPWNvbnN1bWVyc2VjcmV0Jng9OTk-"
  data-p-jan=<?php echo $GetNum ?>
>
{{#ResultSet.0.Result}}
 {{#0}}
 <a href="{{Url}}"><img src="{{Image.Medium}}" alt="{{Name}}"></a>
 {{/0}}
{{/ResultSet.0.Result}}
</script>
</div>
</body>
</html>