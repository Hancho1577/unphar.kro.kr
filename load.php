<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Unphar</title>
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:100,300,400&display=swap&subset=korean" rel="stylesheet">
  <link rel="stylesheet" href="https://ccc1.kro.kr/css/bootstrap.css">
  <style media="screen">
    main {
      margin-top: 1%;
      margin-bottm: 0.5%;
      font-family: 'Noto Sans KR', sans-serif;
      font-weight: 300;
      position: static;
      margin-left: 20%;
      margin-right: 20%;
      color: #2f94e9;
    }

    body {
      margin: 5%;
      font-family: 'Noto Sans KR', sans-serif;
      font-size: 23px;
      color: #dcdbd8;
      background: #212324;
    }

    .alert-light {
      background-color: #18191a;
      border: 1px solid #26264c;
    }

    .jumbotron {
      background-color: #1c1e20;
    }

    .btn-danger {
      background-color: #8c1823;
      border: 1px solid #8c1823;
    }

    .my-4 {
      border: 1px solid #232527;
    }
  </style>
</head>

<body>
  <main>
    <div id="info">
    </div>
    <div id="files">
    </div>
    <script>
      <?php
      $id = $_GET["id"];
      $power = $_GET["power"];
      $dir = "./ZIPS/".$id.
      "/";
      if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'ko') {
        $strings = [
          '메인으로',
          "개",
          '다운로드 가능',
          $leftfiles.
          '개 중 '.$success.
          '개 성공 및 '.$error_.
          '개 실패',
          "취소하기",
          "전체 다운로드",
        ];
        $errors = [
          "잘못된 주소입니다",
          "파일이 첨부되지 않았습니다",
          "알 수 없는 오류가 발생하였습니다",
          "phar파일이 아닙니다",
          "추출하는 도중 오류가 발생했습니다",
          "알 수 없는 오류가 발생했습니다"
        ];
      } else {
        $strings = [
          'home',
          " extracted files",
          //"Unpacking ".$countfiles." PHARs, total: ".$countfiles,
          "You can download",
          $success.
          " of ".$countfiles.
          " successful and ".$error_.
          " failed",
          "cancel",
          "Download All",
        ];
        $errors = [
          "invalid address",
          "This address is no longer valid",
          "An unknown error has occurred",
          "It's not a phar",
          "An error occurred while extracting",
          "An unknown error has occurred"
        ];
      }

      ?>
      function setInfo(arr) {
        document.getElementById("info").innerHTML = '<div class="jumbotron"><font color="#dcdbd8"><h1 class="display-4">' + arr[1] + arr[0] + '</h1><p class="lead"></p><hr class="my-4"><p>' + arr[2] +
          '</p><div id="download"> </div> <div id="all"><a class="btn btn-outline-danger" href="https://ccc1.kro.kr/Hancho/tools/unphar.php" role="button">' + arr[3] + '</a></div></font></div><main>';
      }

      function addFile(str) {
        document.getElementById('files').innerHTML += str;
      }
      <?php
      if (!$id) {
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[0],
            $strings[0],
          ]).
          ');');
      }
      elseif(!$power) {
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[0],
            $strings[0],
          ]).
          ');');
      }
      elseif(!file_exists("./ZIPS/$id/$power")) {
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[0],
            $strings[0],
          ]).
          ');');
      }
      elseif(!is_numeric($id)) {
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[0],
            $strings[0],
          ]).
          ');');
      }
      /*else if (is_dir($dir)){  echo("\n".'setInfo('.json_encode([
        '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
        "Error",
        $errors[1],
        $strings[0],
      ]).');');}*/
      elseif(!count(is_dir($dir)) > 0) {
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[1],
            $strings[0],
          ]).
          ');');
      }
      elseif($dh = opendir($dir)) {
        $adds = [];
        while (false !== ($file = readdir($dh))) {
          if ($file == "." || $file == "..") {
            continue;
          }
          if ($file != 'phars'.$id.
            '.zip') {
            if (!is_dir("./ZIPS/$id/$file")) {
              array_push($adds, "\n".
                'addFile('.json_encode('<div class="alert alert-light" role="alert"> <a href="../ZIPS/'.$id.
                  '/'.$file.
                  '" target="_blank"> '.$file.
                  '</a></div>').
                ')');
            }
          } else {
            $download = true;
          }
        }

        if (!empty($adds)) {
          foreach($adds as $key => $value) {
            echo $value;
          }
          echo("\n".
            'setInfo('.json_encode([
              '<div class="spinner-grow text-success" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
              "Download phars",
              count($adds).$strings[1],
              $strings[0],
            ]).
            ');');
          if (isset($download)) {
            echo("document.getElementById(\"download\").innerHTML = '<a class=\"btn btn-outline-success\" href=\"".
              '../ZIPS/'.$id.
              '/'.
              'phars'.$id.
              '.zip'.
              "\" role=\"button\">".$strings[5].
              "</a>'");
          }
        } else {
          echo("\n".
            'setInfo('.json_encode([
              '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
              "Error",
              $errors[3],
              $strings[0],
            ]).
            ');');
        }
      } else {

        //var_dump($dh);
        echo("\n".
          'setInfo('.json_encode([
            '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>',
            "Error",
            $errors[1],
            $strings[0],
          ]).
          ');');
      }
      closedir($dh);
      ?>
    </script>
  </main>
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="https://ccc1.kro.kr/js/bootstrap.js"></script>
</body>

</html>
