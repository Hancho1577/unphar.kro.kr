<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta property="og:title" content="Unphar - Hancho">
  <meta property="og:description" content="Unpack your Phar">
  <title>Unphar - Hancho</title>
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:100,300,400&display=swap&subset=korean" rel="stylesheet">
  <link rel="stylesheet" href="https://ccc1.kro.kr/css/bootstrap.css">
  <style media="screen">
    main {
      margin: 1%;
      margin-top: 0.5%;
      margin-bottm: 0.5%;

    }

    .box {
      position: absolute;
      padding: 20px;
      font-family: 'Noto Sans KR', sans-serif;
      font-size: 23px;
      color: #f1f5f8;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .boxu {
      position: relative;
    }

    .box_s {

      font-family: 'Noto Sans KR', sans-serif;
      font-weight: 300;
      position: static;
      margin-top: 50%;
      margin-left: 20%;
      margin-right: 20%;
      color: #49A1FF;
    }

    .text_thin {
      font-weight: 100;
    }

    .text_light {
      font-weight: 300;
    }

    .hide {
      visibility: collapse;
    }

    .al {
      text-align: center;
    }

    body {
      font-family: 'Noto Sans KR', sans-serif;
      font-size: 23px;
      color: #e5e9ec;
      background: #17191a;
    }

    .custom-file-input:focus~.custom-file-label-c {
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .custom-file-input:disabled~.custom-file-label-c {
      background-color: #e9ecef;
    }

    .custom-file-input:lang(en)~.custom-file-label-c::after {
      content: "Browse";
    }

    .custom-file-input~.custom-file-label-c[data-browse]::after {
      content: attr(data-browse);
    }

    .custom-file-label-c {
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
      z-index: 1;
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      font-weight: 400;
      line-height: 1.5;
      color: #c0bcb5;
      background-color: #17191a;
      border: 1px solid #565d63;
      border-radius: 0.25rem;
    }

    .custom-file-label-c::after {
      font-family: 'Noto Sans KR', sans-serif;
      font-weight: 100;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      z-index: 3;
      display: block;
      height: calc(1.5em + 0.75rem);
      padding: 0.375rem 0.75rem;
      line-height: 1.5;
      color: #c0bcb5;
      content: "Browse";
      background-color: #1c1e20;
      border-left: inherit;
      border-radius: 0 0.25rem 0.25rem 0;
    }
  </style>

</head>

<body>
  <main>
    <?php
if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'ko') {
    $strings = [
    "UnPHAR",
    "PHAR파일을 쉽게 추출해보세요.",
    "업로드",
    "최대 50MB, 250개",
  ];
} else {
    $strings = [
    "UnPHAR",
    "Easily extract your PHARs",
    "Upload",
    "Up to 50 MB, 250 items",
  ];
}
echo('<div class="box_s"><div  class="box">');
echo('<p><font size="50px" >'.$strings[0].'</font></p>');
echo('<p class="text_light">'.$strings[1].'</p>');
  ?>

    <form action="./upload.php" method="post" enctype="multipart/form-data" id="sendfiles">
      <div class="input-group mb-3" id="inputBox">
        <div class="input-group-prepend">
          <?php
        echo '<button type="submit" name="submit_" class="btn btn-secondary" id="inputGroupFileAddon01" disabled>'.$strings[2].'</button>'
        ?>
        </div>
        <div class="custom-file">
          <input multiple="multiple" type="file" name="myfile[]" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" accept=".phar">
          <label class="custom-file-label-c" id="custom-file-label" for="inputGroupFile01">Choose file</label>
        </div>
      </div>
      <font size="2px">
        <p class="text_thin"><?php echo $strings[3]; ?></p>
      </font>
      <div id="uploadingD">
      </div>
    </form>
    </div>

    </div>
    <div class="ebox">
      <a href="https://github.com/Hancho1577?tab=repositories" target="_top">
        <font color="white" size="2px">@Hancho</font>
      </a>
    </div>
  </main>
  <div id="uploads">
  </div>
  <script type="text/javascript">
    document.getElementById("inputGroupFileAddon01").onclick = function() {
      console.log("ok");
      try {
        //document.getElementById("inputBox").className = "hide"
        document.getElementById("inputGroupFileAddon01").className = "btn btn-primary";

        document.getElementById("inputGroupFileAddon01").innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...'
        var f = document.getElementById("sendfiles")
        f.myfile = JSON.stringify(document.getElementById("inputGroupFile01"));
        //console.log(f);
        f.submit();
      } catch (e) {
        document.getElementById("inputGroupFileAddon01").className = "btn btn-danger";
        document.getElementById("inputGroupFileAddon01").innerHTML = 'ERROR!';
        document.getElementById("inputGroupFileAddon01").disabled = true;
        console.log(e);
      }
      document.getElementById("inputGroupFileAddon01").disabled = true;
    };

    document.getElementById("inputGroupFile01").onchange = function() {
      console.log("ok");
      var uploads = document.getElementById("uploads");

      var files = this.files;
      if (files == null) {
        var files = $(this).val().split("\\").pop();
        if (files) {
          uploads.innerHTML = "";
          document.getElementById("inputGroupFileAddon01").className = "btn btn-success";
          document.getElementById("inputGroupFileAddon01").disabled = false;
          document.getElementById("inputGroupFileAddon01").innerHTML = '<?php echo $strings[2]; ?>';
          document.getElementById('custom-file-label').innerHTML = files;
        }
      } else if (files.length > 0) {
        uploads.innerHTML = "";
        document.getElementById("inputGroupFileAddon01").className = "btn btn-success";
        document.getElementById("inputGroupFileAddon01").disabled = false;
        var fileName = $(this).val().split("\\").pop();
        document.getElementById("inputGroupFileAddon01").innerHTML = '<?php echo $strings[2]; ?>';
        document.getElementById('custom-file-label').innerHTML = files.length + " Items";
      }
    };
  </script>
  <form class="md-form" action="upload.php" method="post" enctype="multipart/form-data">
  </form>
  <div class="container">
  </div>
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  echo '<script type="text/javascript" src="https://ccc1.kro.kr/js/bootstrap.js"></script>';
</body>
</html>
