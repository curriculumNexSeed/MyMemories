<?php

  require('dbconnect.php');

  $title ='';
  $detail='';
  $errors=array();

  if (!empty($_POST)) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $detail = $_POST['detail'];
    $img_name = $_FILES['input_img_name']['name'];

//<<----- バリデーション ----->>
    $count = mb_strlen($title);
    $figue = mb_strlen($detail);

    if ($title == '') {
      $errors['title'] = 'blank';
    } elseif ($count > 24) {
      $errors['title'] = 'length';
    }

    if ($detail == '') {
      $errors['detail'] = 'blank';
    } elseif ($figue > 140) {
      $errors['detail'] = 'length';
    }
//<<----- ここまで ----->>

    if(!empty($img_name)) {
//<<----- 画像のバリデーション ----->>
      $file_type = substr($img_name, -4);
      $file_type = strtolower($file_type);

      if( $file_type != '.jpg' && $file_type != '.png' && $file_type != '.gif' && $file_type != 'jpeg') {
        $errors['img_name'] = 'type';
      }
    } else {
      $errors['img_name']='blank';
    }
//<<----- ここまで ----->>

//<<----- 以降エラーが起きなかった時の処理 ----->>
    if(empty($errors)) {
      date_default_timezone_set('Asia/Manila');
      $date_str = date('YmdHis');
      $submit_file_name = $date_str.$img_name;    //画像名を被らせないようにするため、$date_strで日時を付け足す

      //move_uploaded_file(テンポラリパス、保存したい場所、ファイル名)
      move_uploaded_file($_FILES['input_img_name']['tmp_name'], 'post_img/'.$submit_file_name);


      $sql = 'INSERT INTO `feeds` SET `title`=?, `date`=?, `detail`=?, `img_name`=? ';
      $data = array($title,$date,$detail,$submit_file_name);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);

      header('Location: index.php');
      exit();
      $dbh = null;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>My Memories</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="assets/js/chart.js"></script>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=""><i class="fa fa-camera" style="color: #fff;"></i></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="index.php">Main page</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  
  
  <div class="container">
    <div class="col-xs-8 col-xs-offset-2 thumbnail">
      <h2 class="text-center content_header">写真投稿</h2>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
          <label for="task">タイトル</label>
          <input name="title" class="form-control">
          <?php if (isset($errors['title']) && $errors['title'] == 'blank') { ?>
            <p class="text-danger">タイトルを入力してください</p>
          <?php } ?>
          <?php if (isset($errors['title']) && $errors['title'] == 'length') { ?>
            <p class="text-danger">タイトルの字数が多すぎます</p>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="date">日付</label>
          <input type="date" name="date" class="form-control">
        </div>
        <div class="form-group">
          <label for="detail">詳細</label>
          <textarea name="detail" class="form-control" rows="3"></textarea><br>
          <?php if (isset($errors['detail']) && $errors['detail'] == 'blank') { ?>
            <p class="text-danger">内容を記述して下さい</p>
          <?php } ?>
          <?php if (isset($errors['detail']) && $errors['detail'] == 'length') { ?>
            <p class="text-danger">内容がたっぷりすぎます</p>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="img_name">画像</label>
          <input type="file" name="input_img_name" id="img_name">
        </div><br>
        <input type="submit" class="btn btn-primary" value="投稿">
      </form>
    </div>
  </div>

  <div id="f">
    <div class="container">
      <div class="row">
        <p>I <i class="fa fa-heart"></i> Cubu.</p>
      </div>
    </div>
  </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
