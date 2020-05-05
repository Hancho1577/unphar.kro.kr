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
    <script type="text/javascript">
      function setInfo(arr) {
        document.getElementById("info").innerHTML = '<div class="jumbotron"><font color="#dcdbd8"><h1 class="display-4">' + arr[1] + arr[0] + '</h1><p class="lead">' + arr[2] + '</p><hr class="my-4"><p>' + arr[3] +
          '</p> <div id="all"><a class="btn btn-danger btn-lg" href="./unphar.php" role="button">' + arr[4] + '</a></div></font></div><main>';
      }

      function addFile(str) {
        document.getElementById('files').innerHTML += str;
      }

      <?php
      $realname = "";
      $countfiles = count($_FILES['myfile']['name']);
      $leftfiles = count($_FILES['myfile']['name']);
      $error_ = 0;
      $success = 0;
      if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'ko') {
        $strings = [
          '<div class="spinner-grow text-warning" role="status"><span class="sr-only">Loading...</span></div>',
          //"변환중",
          "",
          $leftfiles.
          "개의 phar변환 중, 총 ".$countfiles.
          '개',
          $leftfiles.
          '개 중 '.$success.
          '개 성공 및 '.$error_.
          '개 실패',
          "취소하기",
          "전체 다운로드",
        ];
        $errors = [
          "파일이 너무 큽니다",
          "파일이 첨부되지 않았습니다",
          "파일이 제대로 업로드되지 않았습니다",
          "phar파일이 아닙니다",
          "추출하는 도중 오류가 발생했습니다",
          "알 수 없는 오류가 발생했습니다"
        ];
      } else {
        $strings = [
          '<div class="spinner-grow text-warning" role="status"><span class="sr-only">Loading...</span></div>',
          "Extracting",
          //"Unpacking ".$countfiles." PHARs, total: ".$countfiles,
          "",
          $success.
          " of ".$countfiles.
          " successful and ".$error_.
          " failed",
          "cancel",
          "Download All",
        ];
        $errors = [
          "The file is too large",
          "File not attached",
          "File is not uploaded properly uploaded",
          "It's not a phar",
          "An error occurred while extracting",
          "An unknown error has occurred"
        ];
      }
      echo("\n".
        'setInfo('.json_encode($strings).
        ');');
      $id = mt_rand(1000000000000, 9999999999999);
      $datas = [];
      $adds = [];
      $adds_e = [];

      // 어디서 가져온 소스인지 모르겠음
      function GenerateString($length) {
      $characters = '0123456789';
      $characters .= 'abcdefghijklmnopqrstuvwxyz';
      $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $characters .= '_';

      $string_generated = '';

      $nmr_loops = $length;
      while ($nmr_loops--) {
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];
      }

      return $string_generated;
    }
      for ($i = 0; $i < $countfiles; $i++) {
        $uploads_dir = './files';
        $allowed_ext = array('phar');

        //출처: https://jhrun.tistory.com/197 [JHRunning]
        $error = $_FILES['myfile']['error'][$i];
        $name = $_FILES['myfile']['name'][$i];
        $ext = array_pop(explode('.', $name));
        array_pop(explode('.', $name));
        $nomalName = $name;
        $string = "";
        // 오류 확인
        if ($error != UPLOAD_ERR_OK) {
          switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
              $string = $errors[0];
              break;
            case UPLOAD_ERR_NO_FILE:
              $string = $errors[1];
              break;
            default:
              $string = $errors[2];
          }
        }
        if (!in_array($ext, $allowed_ext)) {
          $string = $errors[3];
        }
        if ($string == "") {
          $ex = "";
          try {
            @mkdir("./phars/$id/");
            move_uploaded_file($_FILES['myfile']['tmp_name'][$i], "./phars/$id/".$name);
            
            $phar = new Phar("./phars/$id/".$name);
            $fd_name = mt_rand(100, 1000).$name;
            @mkdir("./finished/$id/");
            $phar -> extractTo("./finished/$id/".$fd_name);
          } catch (Exception $e) {
            $string = $errors[4];
            $ex = $e;
          }
          if (!$string == $errors[4]) {
            try {
              $path = "./finished/$id/".$fd_name;
              $zip = new\ ZipArchive;
              @mkdir('./ZIPS/'.$id.
                '/', 0755);
              /*if (in_array($name, $datas)) {
                $t = explode(".", $name);
                $t[count($t) - 1] = mt_rand(0, 10000).
                ".phar";

                $realname = implode("", $t);
              } else {
                $t = explode(".", $name);
                $t[count($t) - 1] = "";
                implode("", $t);
                $realname = implode("", $t);
              }*/
              $realname = $i;

              $zip -> open('./ZIPS/'.$id.
                '/'.$realname.
                '.zip', $zip::CREATE | $zip::OVERWRITE);
              $files = new\ RecursiveIteratorIterator(
                new\ RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY
              );
              foreach($files as $datos) {
                if (!$datos->isDir()) {
                  $relativePath = $fd_name.
                  '/'.substr($datos, strlen($path) + 1);
                  $zip->addFile($datos, $relativePath);
                }
              }
              $zip->close();
            } catch (Exception $e) {
              $string = $errors[5];
            }
          } else {
            if (strpos($ex, "truncated manifest header")) {
              $string .= ' , It can be a corrupted phar';
            }
          }
        }
        // 파일 이동
        if ($string == "") {
          $success++;
          array_push($adds, "\n".
            'addFile('.json_encode('<div class="alert alert-light" role="alert"> <a href="./ZIPS/'.$id.
              '/'.$realname.
              '.zip'.
              '" target="_blank"> '.$name.
              '</a></div>').
            ')');
        } else {
          $error_++;
          array_push($adds_e, "\n".
            'addFile('.json_encode('<div class="alert alert-light" role="alert"> '.$name.
              '			('.$string.
              ')</div>').
            ')');
        }
      }
      if ($realname != "") {
        try {
          $path = "./finished/$id/";
          @mkdir('./ZIPS/'.$id.
            '/', 0755);
          $realname = 'phars'.$id.
          '.zip';

          $path2 = './ZIPS/'.$id.
          '/'.$realname;
          shell_exec("zip -r ./ZIPS/$id/$realname ./finished/$id/*");
          echo("\n\n\n");
        } catch (Exception $e) {
          $realname = "";
        }
      }
      $ps = GenerateString(5);
      if ($error_ == $countfiles) {
        $sc = '<div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>';
      }
      else if($error_ > 0) {
        @mkdir("./ZIPS/$id/$ps");
        $sh = true;
        $sc = '<div class="spinner-grow text-warning" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>';
      } else {
        @mkdir("./ZIPS/$id/$ps");
        $sh = true;
        $sc = '<div class="spinner-grow text-success" style="width: 3rem; height: 3rem;" role="status">  <span class="sr-only">ok</span></div>';
      }
      if (!empty($adds)) {
        foreach($adds as $key => $value) {
          echo $value;
        }
      }
      if (!empty($adds_e)) {
        foreach($adds_e as $key => $value) {
          echo $value;
        }
      }
      if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'ko') {
        $strings = [
          $sc,
          "완료",
          "",
          $leftfiles.
          '개 중 '.$success.
          '개 성공 및 '.$error_.
          '개 실패',
          "뒤로가기",
          "전체 다운로드",
          "공유",
        ];
      } else {
        $strings = [
          $sc,
          "Finished",
          "",
          $success.
          " of ".$countfiles.
          " successful and ".$error_.
          " failed",
          "back",
          "Download All",
          "Share",
        ];
      }
      echo("\n".
        'setInfo('.json_encode($strings).
        ');');
      if ($realname != "") {
        echo("\n".
          "document.getElementById(\"all\").innerHTML = '<a class=\"btn btn-outline-success\" href=\"".
          './ZIPS/'.$id.
          '/'.$realname.
          "\" role=\"button\">".$strings[5].
          "</a>'");
      }
      if (isset($sh)) {
        echo("\n".
          "document.getElementById(\"all\").innerHTML += '  <a class=\"btn btn-outline-primary\" aria-expanded=\"false\" aria-controls=\"collapseExample\" data-target=\"#collapseExample\" data-toggle=\"collapse\" href=\"".
          './load.php/?id='.$id.
          "&power=$ps\" role=\"button\">".$strings[6].
          "</a><div class=\"collapse\" id=\"collapseExample\">https://ccc1.kro.kr/Hancho/tools/load.php/?id=$id&power=$ps</div>'");
      }
      echo("\n".
        "document.getElementById(\"all\").innerHTML += '  <a class=\"btn btn-outline-danger\" href=\"./unphar.php\" role=\"button\">".$strings[4].
        "</a>'");
        ?>
    </script>
  </main>
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="https://ccc1.kro.kr/js/bootstrap.js"></script>
</body>

</html>
