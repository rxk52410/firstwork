<html>
<head>
<meta charset="UFT-8">
<title>FORUM</title>
</head>
<body bgcolor="lavenderblush">

<font size="7" color="bluevioret">FORUM</font>
<hr>
<hr>

<?php
//データベース接続

$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password);

//テーブル作成

$sql="CREATE TABLE forum"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
."password TEXT"
.");";
$stmt=$pdo->query($sql);

?>


<!--受け取り-->
<?php
if(isset($_POST['name'])){
$name=$_POST['name'];
}
if(isset($_POST['comment'])){
$coment=$_POST['comment'];
}
if(isset($_POST['submit'])){
$submit=$_POST['submit'];
}
if(isset($_POST['edit_num'])){
$edit_num=$_POST['edit_num'];
}
if(isset($_POST['password'])){
$password=$_POST['password'];
}
if(isset($_POST['password3'])){
$password3=$_POST['password3'];
}
if(isset($_POST['pass3'])){
$pass3=$_POST['pass3'];
}
if(isset($_POST['edit'])){
$edit=$_POST['edit'];
}
if(isset($_POST['password2'])){
$password2=$_POST['password2'];
}
if(isset($_POST['delete'])){
$delete=$_POST['delete'];
}
?>




<!--新規投稿-->
<?php
//名前、コメント、パスワードがあり、編集番号がないとき
if(!empty($_POST['submit'])&&empty($_POST['edit_num'])&&!empty($_POST['password'])){

$sql=$pdo->prepare("INSERT INTO forum(name,comment,date,password)VALUES(:name,:comment,:date,:password)");

$sql->bindParam(':name',$name,PDO::PARAM_STR);
$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
$sql->bindParam(':date',$date,PDO::PARAM_STR);
$sql->bindParam(':password',$password,PDO::PARAM_STR);

$name=$_POST['name'];
$comment=$_POST['comment'];
$date=new DateTime();
$date=$date->format('Y-m-d H:i:s');
$password=$_POST['password'];

$sql->execute();

//echo "新規投稿分岐";

}

?>


<!--削除処理-->
<?php

//削除番号とパスワードがあるとき
if(!empty($_POST['password2'])&&!empty($_POST['delete'])){
	$sql="SELECT*FROM forum where id=:delete";
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':delete',$delete,PDO::PARAM_INT);
	$stmt->execute();
	
	foreach($stmt as $row){
		
		//パスワードチェック
		if($password2==$row['password']){
		//echo "パスワード一致";
		
		$sql='DELETE FROM forum where id=:delete';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':delete',$delete,PDO::PARAM_INT);
		$stmt->execute();
		
		echo "削除しました。";
		
		}elseif($password2!=$row['password']){
		echo "パスワードが違います。";
		}
	}


}

?>

<!--編集処理-->
<?php

//名前、コメント、パスワード、編集番号があるとき
if(!empty($_POST['submit'])&&!empty($_POST['edit_num'])&&!empty($_POST['pass3'])){

$sql='UPDATE forum SET name=:name, comment=:comment, date=:date, password=:password WHERE id=:edit_num';
$stmt=$pdo->prepare($sql);

$stmt->bindParam(':name',$name,PDO::PARAM_STR);
$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
$stmt->bindParam(':date',$date,PDO::PARAM_STR);
$stmt->bindParam(':password',$password,PDO::PARAM_STR);
$stmt->bindParam(':edit_num',$edit_num,PDO::PARAM_INT);

$name=$_POST['name'];
$comment=$_POST['comment'];
$date=new DateTime();
$date=$date->format('Y-m-d H:i:s');
$password=$_POST['password'];

$stmt->execute();


//echo "編集分岐";
}

?>

<!--編集機能-->
<?php

//
if(!empty($_POST['edit'])){

	$sql="SELECT*FROM forum where id=:edit";
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':edit',$edit,PDO::PARAM_INT);
	$stmt->execute();
	foreach($stmt as $row){
		
		//パスワードチェック
		if($password3==$row['password']){
		$nm=$row['name'];
		$kome=$row['comment'];
		//echo "パスワード一致";
		}else{
		echo "パスワードが違います。";
		}
	}

}

if(!empty($_POST['submit'])&&!empty($_POST['edit_num'])&&!empty($_POST['pass3'])){
	
	$sql="SELECT*FROM forum where id=:edit_num";
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':edit_num',$edit_num,PDO::PARAM_INT);
	$stmt->execute();
	
	foreach($stmt as $row){
	
		if($pass3==$row['password']){
		echo "編集しました。";
		}
	}
}
?>


<!--送信フォーム-->
<form action="mission_4.php" method="post">
<input type="text" name="name" value="<?php echo $nm; ?>" placeholder="名前"><br>
<input type="text" name="comment" value="<?php echo $kome; ?>" placeholder="コメント"><br>
<input type="text" name="password" value="<?php echo $password3; ?>" placeholder="パスワード">
<input type="hidden" name="pass3" value="<?php echo $password3; ?>">
<input type="hidden"  name="edit_num" value="<?php echo $edit; ?>">
<input type="submit" name="submit" value="送信">
</form>

<!--削除フォーム-->
<form action="mission_4.php" method="post">
<input type="number" name="delete" placeholder="削除対象番号"><br>
<input type="text" name="password2" placeholder="パスワード">
<input type="submit" value="送信">
</form>

<!--編集フォーム-->
<form action="mission_4.php" method="post">
<input type="number" name="edit" placeholder="編集対象番号"><br>
<input type="text" name="password3" placeholder="パスワード">
<input type="submit" value="送信">
</form>

<hr>
<hr>

<font size="6" color="hotpink">投稿内容</font><br>

<!--画面表示-->
<?php

$sql='SELECT *FROM forum ORDER BY id';
$results=$pdo->query($sql)->fetchAll();
if($results===false){
echo 'まだ投稿はありません。';
 }else{
	foreach($results as $row){
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';

	}
}

?>


</body>
</html>