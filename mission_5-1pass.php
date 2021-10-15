<!DOCTYPE html>
<html lang="ja">
    
    <head>
        <meta charset="UTF-8">
        <title>mission5-1pass</title>
    </head>
    
    <body>
        
        <?php
        //DB接続設定
        $dsn="データベース名";
        $user="ユーザー名";
        $password="パスワード";
        $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
        
        //テーブル作成
         $sql = "CREATE TABLE IF NOT EXISTS tbtest"
         //IF NOT EXISTS=もしまだこのテーブルが存在しないなら
         ."("
         ."id INT AUTO_INCREMENT PRIMARY KEY,"
         //id＝自動で登録されるナンバリング
         ."name char(32),"
         //名前を入れる。文字列、半角英数で32文字。
         ."comment TEXT,"
         //コメントを入れる。文字列、長めの文章も入る。
         ."date DATETIME,"
         ."pass TEXT"
         .");";
         $stmt = $pdo->query($sql);
        ?>
        
       <?php 
       //投稿機能INSERT文
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["edit_n"]) && !empty($_POST["pass"] )){ 
            $sql = $pdo->prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
            $sql-> bindParam(":name", $name, PDO::PARAM_STR);
            $sql-> bindParam(":comment", $comment, PDO::PARAM_STR);
            $sql-> bindParam(":date", $date, PDO::PARAM_STR);
            $sql-> bindParam(":pass", $pass, PDO::PARAM_STR);
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $date=date("Y/m/d/H/i/s");
            $pass=$_POST["pass"];
            $sql-> execute();
            
        }
        //削除機能　DELETE文
        if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
            $delete=$_POST["delete"];
            $delpass=$_POST["delpass"];
            $sql="delete from tbtest where id=:id AND pass=:pass";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":id",$delete, PDO::PARAM_INT);
            $stmt->bindParam(":pass",$delpass, PDO::PARAM_INT);
            $stmt->execute();
            
        }
        
        //編集選択機能 SELECT文
        if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
            $edit=$_POST["edit"];
            $editpass=$_POST["editpass"];
             $sql = 'SELECT * FROM tbtest where id=:id AND pass=:pass';
       $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id",$edit, PDO::PARAM_INT);
            $stmt->bindParam(":pass",$editpass, PDO::PARAM_INT);
            $stmt->execute();
       $results = $stmt->fetchAll();
       foreach ($results as $row){
       $editnumber = $row["id"];
       $editname = $row["name"];
       $editcomment = $row["comment"];
       
        }
        }
    
        
        //編集実行機能　UPDATE文
        if (!empty($_POST["edit_n"]) && !empty($_POST["pass"])) {
            $id_edit=$_POST["edit_n"];
            //変更する投稿番号
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $pass=$_POST["pass"];
            //変更したい名前、コメント
            $sql="UPDATE tbtest SET  name=:name,comment=:comment WHERE id=:id AND pass=:pass";
            $stmt= $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_edit, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            
        }
        ?>
           
    　　 <form action="" method="post">
           名前： <input type="text" name="name" value=<?php echo @$editname;?>><br>
           コメント： <input type="text" name="comment"value=<?php echo @$editcomment;?>><br>
            
            <input type="text" name="pass" placeholder="パスワード"<?php echo @$editpass;?>><br>
            <input type="submit" name="submit" value="送信"><br>
            <br>
            <input type="number" name="delete" placeholder="削除対象番号"><br>
             <input type="text" name="delpass" placeholder="パスワード">
            <input type="submit" name="submit_del" value="削除"><br>
             <br>
            
      <input type="text" name="edit" placeholder="編集対象番号入力"><br>
      <input type="text" name="editpass" placeholder="パスワード" >
            <input type="submit" name="submit_edi" value="編集">
            <input type="hidden" name="edit_n" value=<?php echo @$editnumber;?> >
       </form>
       
       <?php
       //表示機能  SELECT機能
       $sql = 'SELECT * FROM tbtest';
       $stmt = $pdo->query($sql);
       $results = $stmt->fetchAll();
       foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].',';
        echo "<hr>";
       }
    ?>
       
        
        </body>
        </html> 