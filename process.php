<?php
error_reporting(E_ALL);
ini_set('display_errors' ,1);

$file = "student.txt";
//لو عايز يعرض الطلاب
if(isset($_GET['view'])){
    echo "<!DOCTYPE html>
    <html lang='ar' dir='rtl'>
    <head>
    <meta charset= 'UTF-8'>
    <title>قائمة الطلاب</title>
    <style>
    body {
    font-family: Tahoma; 
    background: #f4f6f9;
    padding: 30px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
    }
    th {
    background: #3498db;
    color : white;
    }
    tr :nth-child(even) {
    background: #f2f2f2;
    }
    h2 {
    text-align: center;
    color : #2c3e50;
    }

    a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #3498db;
    color : white;
    text-decoration : none;
    border-radius: 5px;
    }
    </style>
    </hred>
    <body>
    <h2>قائمة الطلاب المسجلين</h2>
    <table>
    <tr>
    <th>اسم الطالب</th>
    <th> البريد الالكتروني</th>
    <th>رقم الطالب</th>
    <th>سنة الدراسة</th>
    <th>اسم الدفعة</th>
    <tr>";
    if(file_exists($file)){
        $lines =file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
            $data =explode("|", $line);
            echo "<tr>";
            foreach($data as $d){
                echo "<td>" .htmlspecialchars($d) . "</td>";
            }
            echo "</tr>";
        }

    }
    else {
        echo "<tr><td colspan='5'>لا يوجد طلاب مسجلين</td><tr>";
    }
    echo "</table><div style= 'text-align:center;'><a href= 'index.html'></a></div></body></html>";
exit;

}
//لو جات بيانات من الفورم

if($_SERVER["REQUEST_METHOD"] == "POST") {

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$student_id = trim($_POST['student_id']);
$year = trim($_POST['year']);
$batch = trim($_POST['batch']);
//التاكد انو مافي حقل فاضي

if(!empty($name) &&! empty($email) &&!  empty($student_id) &&! empty($year) &&! empty($batch)){

//التاكد ما نكرر رقم الطالب

$exists = false;
if(file_exists($file)){
    $line = file($file, FILE_IGNORE_NEW_LINES);
foreach($line as $line){
        $data = explode("|", $line);
        if(isset($data[2]) && $data[2] == $student_id){
            $exists = true;
            break;
        }
    }
}
if($exists){
    echo "<script> alert('!رقم الطالب مسجل مسبقا'); window.location= 'index.html';</script>";

}
else{
    //حفظ البيانات في الملف
    $data = "$name| $email |$student_id|$year|$batch\n";
    file_put_contents($file, $data, FILE_APPEND); 

    echo "<!DOCTYPE html>
    <html lang='ar' dir='rtl'>
    <head>
    <meta charset='UTF-8'>
    <title>تم التسجيل</title>
    <style>
    body{
    font-family: tahoma;
    background: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    }
    
    .msg{
        background: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow:0 4px 15px rgba(0,0,0,0.1);

    }
    .success{
        color: green; 
        font-size: 20px;
    }
    a{display: inline-block;
    margin: 10px;
    padding: 10px 20px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;

    }
    </style>
    </head>
    <body>
<div class='msg'>
<p class='success'>تم تسجيل  الطالب بنجاح</p>
<p><strong>الاسم:</strong> $name </p>
<p><strong>رقم  الطالب:</strong> $student_id</p>
<a href='index.html'>تسجيل طالب اخر</a>
<a href ='process.php?view=1'>عرض القائمة</a>
</div>
</body>
</html>";
}
}
else{
    echo "<script>alert('املئي كل الحقول');window.location='index.html';</script>";
}
}
?>