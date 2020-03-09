<html >
<head>
<title>勤益最大的卜卦站</title>
</head>

    <body>
        <script type="text/javascript"></script>
        <audio src="test/music.mp3" controls="controls" hidden='true' id="music1" autoplay>
        </audio>
        
        <script>
            function bofang(num){
                var audio = document.getElementById('music1');
                if(audio!==null){//判断是否获得
                    if(audio.paused && num==1){//获得播放状态,这个属性应该是是否是暂停状态,如果是就播放,如果不是暂停就暂停
                        audio.play();
                    }else{
                        audio.pause();
                    }
                }
            }
        </script>
    </body>
</html>
<?php
session_start();
include("include.php");

$size=4;

//背景
echo "<table align='' border='0' background='test/blue.png' width='1920' height='300' >";

//設定資料庫看得懂中文
mysqli_query($db,'set character set utf-8');

echo "<form method='post' >";

if($_SESSION["user"]){
  $user=$_SESSION["user"];
  echo "<td align='center'><q>".$_SESSION["user"]."你好	";
  echo "<a href='find.php'>查詢</a>";echo "  ";
  echo "<a href='changepw.php'>修改密碼</a>";echo "  "."</q>";
  echo "<input type='submit' name='unset' value='登出'>";
echo '<input type="button" value="暫停" onclick="bofang(0)">';
echo '<input type="button" value="播放" onclick="bofang(1)">'."</td>";
}
else
{
  $user="";
  echo "<td align='center' ><q>"."遊客你好	";
  echo "<a href='login.php'>登入</a>"."  ";
  echo "<a href='registered.php'>註冊</a>"."</q>";
echo '<input type="button" value="暫停" onclick="bofang(0)">';
echo '<input type="button" value="播放" onclick="bofang(1)">'."</td>";
}

echo "</table>";

if($_POST["unset"])
{
  session_unset();
  header('location:test.php');
}
echo "</form>";

$color='#00fff0';
$fortune=array("111","110","101","100","011","010","001","000");
//"_"為辨認符號
$ans=array("_111","_110","_101","_100","_011","_010","_001","_000");
//乾兌離震巽坎艮坤
$chinese_ans=array("乾","兌","離","震","巽","坎","艮","坤");

//解碼
header("content-type:text/html;charset=utf-8");

//背景
echo "<table align='center' border='0' background='test/29.jpg' width='1920' height='1080' >"."<td>";

//建立表格
echo "<table align='center' border='5' background='test/meow.jpg' width='1000' >";

//建立form
echo "<form method='post'>";

//說明
echo "<tr><td align='center' colspan='4'>"."<font size='6' color='$color'>"."說明"."</td></tr>";
echo "<tr><td align='center' colspan='4'>"."<font size='6' color='$color'>"."請先想好所要詢問的問題，再想三個數字，<br>把數字寫在下方後送出，將得到問題的建議。"
."</td></tr>";
//第一欄
echo "<td align='center' colspan='4'>"."<font size='6' color='$color'>"."請輸入3個數字"."</td>";
//第2~4欄輸入數字
echo "<tr>";
echo "<td align='center' colspan='4'>"."<font size='6' color='$color'>"."數字1"."<input type='text' name='number[]'>"."<br>";
echo "數字2"."<input type='text' name='number[]'>"."<br>";
echo "數字3"."<input type='text' name='number[]'>"."<br>";
echo "<input type='submit' value='送出' name='output'>";
echo "<input type='submit' value='老天決定' name='god'></td>";
echo "</tr>";

if(($_POST["output"] || $_POST["god"]) )
{

//把數字存在number變數
$number=$_POST["number"];


//老天決定
if($_POST["god"])
{
$number[0]=rand();
$number[1]=rand();
$number[2]=rand();
}

//算出第幾爻
$number[0]=$number[0]%8;
$number[1]=$number[1]%8;
$number[2]=$number[2]%6;

//找出有0的結果，並賦予它正確的編號
for($i=0;$i<count($number)-1;$i++)
{
  //第3個數字規則不一樣
  if($number[$i] == 0)
    $number[$i]=8;
}
  //第三個數字
  if($number[2] == 0)
    $number[2]=6;
  
//第一個結果

$_SESSION['now2']=$number[2];
$_SESSION['now1']=$number[1];
$_SESSION['now0']=$number[0];

}
$number[2]=$_SESSION['now2'];
$number[1]=$_SESSION['now1'];
$number[0]=$_SESSION['now0'];

//現在
echo "<tr>";
echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>"."現在"."</td>";
$now=($chinese_ans[$number[1]-1]).($chinese_ans[$number[0]-1]);
change($now);

echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>".$now."</td>";
//文字說明
$now_explan=explanation($now);
echo "<td align='center' rowspan='2' >"."<font size='$size' color='$color'>".$now_explan."</td>";


echo "<td background='test/$number[1].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";
 
echo "<tr>";
echo "<td background='test/$number[0].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";

//結果運算
$answer=$fortune[$number[1]-1].$fortune[$number[0]-1];

//substr_replace ( $string , $replacement , $start , $length )
//參數說明：$string 是原始字串 , $replacement 是要替換的新字串 
//, $start 是原始字串要開始替換的位置 , $length 是要替換的字串長度。
if($answer[$number[2]-1]==0)
$answer=substr_replace($answer,"1",$number[2]-1,1);
else
$answer=substr_replace($answer,"0",$number[2]-1,1);

//結果回傳，"_"為辨認符號
$number[0]="_".$answer[0].$answer[1].$answer[2];
$number[1]="_".$answer[3].$answer[4].$answer[5];

//結果轉成數字
for($i=0;$i<count($fortune);$i++)
{
  if($number[0]==$ans[$i])
     $number[0]=$i+1;
  if($number[1]==$ans[$i])
     $number[1]=$i+1;  
}


//結果
echo "<tr>";
echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>"."結果"."</td>";

$future=($chinese_ans[$number[1]-1]).($chinese_ans[$number[0]-1]);
change($future);

echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>".$future."</td>";
//文字說明
$future_explan=explanation($future);
echo "<td align='center' rowspan='2'>"."<font size='$size' color='$color'>".$future_explan."</td>";

echo "<td background='test/$number[1].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";


echo "<tr>";
echo "<td background='test/$number[0].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";



//過程
$number[0]="_".$answer[1].$answer[2].$answer[3];
$number[1]="_".$answer[2].$answer[3].$answer[4];

//結果轉成數字
for($i=0;$i<count($fortune);$i++)
{
  if($number[0]==$ans[$i])
     $number[0]=$i+1;
  if($number[1]==$ans[$i])
     $number[1]=$i+1;  
}

echo "<tr>";
echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>"."過程"."</td>";

$process=($chinese_ans[$number[1]-1]).($chinese_ans[$number[0]-1]);
change($process);

echo "<td align='center' rowspan='2'>"."<font size='6' color='$color'>".$process."</td>";
//文字說明
$process_explan=explanation($process);
echo "<td align='center' rowspan='2'>"."<font size='$size' color='$color'>".$process_explan."</td>";

echo "<td background='test/$number[1].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";


echo "<tr>";
echo "<td background='test/$number[0].jpg'  style='width:96px;height:80px;' >"."</td>";
echo "</tr>";



//結束表格與form
echo "</table>"."</form>";

echo "</td>"."<table>";




//現在日期
$getDate= date("Y-m-d");
//如果有使用者就儲存紀錄
if($user && ($_POST["output"] || $_POST["god"])){

$sql_account="INSERT INTO connect(account,now,process,future,date) VALUES ('$user','$now','$process','$future','$getDate')";
$result=mysqli_query($db,$sql_account);

}


function change(&$a)
{
include("include.php");

$sql_find="select name from mur where remark='$a'";
$result=mysqli_query($db,$sql_find);

while($row=mysqli_fetch_array($result))
{
   foreach($row as $item => $value)
    {
     $a=$value; 
    }  

}

}
function explanation($a)
{
include("include.php");

$sql_find="select explanation from mur where name='$a'";
$result=mysqli_query($db,$sql_find);

while($row=mysqli_fetch_array($result))
{
   foreach($row as $item => $value)
    {
     return $value; 
    }  

}

}

?>
        <style> 
            q { 
                color: #ffffff; 
                font-style: ; 
		font-size: 5em;
            } 
        </style>  