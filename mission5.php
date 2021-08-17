<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5</title>
    </head>
    <body>
    <?php
        $hname="";
        $hcome="";
        $name=$_POST["name"];
        $come=$_POST["come"];
        $numh=$_POST["numh"];
        $dele=$_POST["dnum"];
        $henn=$_POST["henn"];
        $pass=$_POST["pass"];
// DB接続設定
        $db = '****';
        $user = '****';
        $password = '****';
        $pdo = new PDO($db, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS mission_5" 
              ."(id INT AUTO_INCREMENT PRIMARY KEY,"
              ."name char(32),comment TEXT,date TEXT,pass char(16));";
        $stmt = $pdo->query($sql);  $dpass=$_POST["dpass"];
            $hpass=$_POST["hpass"];
            $date=date("Y/m/d H:i:s");
            $msg="";
//編集フォーム
            if(isset($_POST['hensyu'])){
                if(empty($henn)&&empty($hpass)){
                    $msg="入力してください";
                }elseif(empty($henn)){
                    $msg="編集する番号を入力してください";
                }elseif(empty($hpass)){
                    $msg="パスワードを入力してください";
                }else{
            //編集するデータを表示
                    $sql= 'SELECT * FROM mission_5 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                 
                    $stmt->bindParam(':id', $henn, PDO::PARAM_INT); 
                    $stmt->execute();                             
                    $results = $stmt->fetchAll();
                //パスワード照合
                    $a=0;
                    foreach ($results as $row){
                        if($hpass==$row['pass']){
                            $a=1;
                        }
                    }
                    if($a==1){
                        $hname=$row['name'];
                        $hcome=$row['comment'];
                        $hnum=$henn;
                    }else{
                        $msg="パスワードが違います";
                    }
                }
            }
        ?>
        <form action=""method="post">
            [　投稿フォーム　]<br>
            <input type="text" name="name"value="<?php echo $hname;?>">/名前<br>
            <input type="text" name="come"value="<?php echo $hcome;?>">/コメント<br>
            <input type="text" name="pass"value="">/パスワード<br>
            <input type="submit" name="sousinn"value="送信"><br>
            <input type="hidden" name="numh"value="<?php echo $hnum;?>"><br>
            [　削除フォーム　]<br>
            <input type="text" name="dnum"value="">/投稿番号<br>
            <input type="text" name="dpass"value="">/パスワード<br>
            <input type="submit" name="dele"value="削除">
            <br><br>
            [　編集フォーム　]<br>
            <input type="text" name="henn"value="">/投稿番号<br>
            <input type="text" name="hpass"value="">/パスワード<br>
            <input type="submit" name="hensyu"value="編集"><br>
        </form>
<?php
//投稿フォーム
    if(isset($_POST['sousinn'])){
            if(empty($name)&&empty($come)||$name=="名前"&&$come=="コメント"){
                $msg="入力してください";
            }elseif(empty($name)||$name=="名前"){
                $msg="名前を入力してください";
            }elseif(empty($come)||$come=="コメント"){
                $msg="コメントを入力してください";
            }elseif(empty($pass)||$pass=="パスワード"){
                $msg="パスワードを入力してください";
            }elseif(empty($numh)||$numh==0){
    //新規入力
                $sql = $pdo -> prepare("INSERT INTO mission_5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
                $sql -> bindParam(':name',$name, PDO::PARAM_STR);
                $sql -> bindParam(':comment',$come, PDO::PARAM_STR);
                $sql -> bindParam(':date',$date, PDO::PARAM_STR);
                $sql -> bindParam(':pass',$pass, PDO::PARAM_STR);
                $sql -> execute(); 
                $msg=$name."を受け付けました";
            }else{
    //編集するデータを表示
            $sql= 'SELECT * FROM mission_5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                 
            $stmt->bindParam(':id', $numh, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll();
    //パスワード照合
            $a=0;
            foreach ($results as $row){
                if($pass==$row['pass']){
                    $a=1;
                }
            }
            if($a==1){
    //編集内容を入力(内容を書き換え)
                $sql = 'UPDATE mission_5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $come, PDO::PARAM_STR);  
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $numh, PDO::PARAM_INT);
                $stmt->execute();                   
            }else{
                $msg="パスワードが違います";
            }
        }
    }
    
//削除フォーム    
    if(isset($_POST['dele'])){
        if(empty($dele)&&empty($dpass)){
            $msg="入力してください";
        }elseif(empty($dele)){
            $msg="削除する番号を入力してください";
        }elseif(empty($dpass)){
            $msg="パスワードを入力してください";
        }else{
    //編集するデータを表示
            $sql= 'SELECT * FROM mission_5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                 
            $stmt->bindParam(':id', $dele, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll();
        //パスワード照合
            $a=0;
            foreach ($results as $row){
                if($dpass==$row['pass']){
                    $a=1;
                }
            }
            if($a==1){
                //特定のデータ削除 
                $sql = 'delete from mission_5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $dele, PDO::PARAM_INT);
                $stmt->execute();
            }else{
                $msg="パスワードが違います";
            }
        }
    }
    
//ブラウザに書き出し
    if(empty($msg)){
        echo "<br><br><br><br>";
    }else{
        echo"<br>".$msg."<br><br>";
    }
    $sql = 'SELECT * FROM mission_5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'];
    echo "<hr>";
    }
        
?>
    </body>
</html>