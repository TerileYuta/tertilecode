<?php
  session_start();
  ini_set('display_errors', 0);

  if(isset($_SESSION["key"])){
    $dsn = "mysql:host=localhost;dbname=tertilecrystal_code";
    $user_name = "tertilecrystal_code";
    $password = "h8et6ruj";

    $file_name = $_SESSION["key"];

    try{
      $pdo = new PDO($dsn, $user_name, $password);

      $sql = "SELECT url FROM code WHERE key_name='". $file_name ."'";
      $res = $pdo->query($sql);
  
      foreach($res as $value){
        $file_name_result = "./CodeFile/". $value['url']. "/index.html";
      }
    }catch(PDOException $e){
      
    }

    
  }else{
    $file_name = "-";
    $file_name_result = "https://app.tertilecrystal.com/TertileCode/create_file.html";
    $code = "";
  }
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">

    <title>TertileCode</title>

    <style>
      *{
        
      }

      body{
        position: relative;
        overflow: hidden;

      }

      :not(#editor_col){
        
      }

      header{
        height: 5vh
      }

      ::-webkit-scrollbar{
          width: 10px;
      }
      ::-webkit-scrollbar-track{
          background: #fff;
          border: none;
          border-radius: 10px;
          box-shadow: inset 0 0 2px #777; 
      }
      ::-webkit-scrollbar-thumb{
          background: #ccc;
          border-radius: 10px;
          box-shadow: none;
      }

      .icon:hover{
        background-color: #a9a9a9;
        cursor: pointer;
      }

      .menu{
        top: 0;
        left: 0;
        width: 306px;
        height: 100vh;
        margin: 0 0;
        z-index: 9999;
        background-color:rgb(51, 51, 51); 
        color: white;
        position: absolute;
        border-right: 1px solid white;
      }

      .menu_btn:hover{
        border: 1px solid white;
      }
      .acd-check{
          display: none;
      }
      .acd-label{
          background: #333;
          color: #fff;
          display: block;
      }
      .acd-content{
          position: absolute;
          border: 1px solid #333;
          opacity: 0;
          left: 0;
          top: 0;
          transition: .25s;
          height: 100vh;
          width: 0px;
          visibility: hidden;
      }
      .acd-check:checked + .acd-label + .acd-content{
          top: 0;
          left: 0;
          width: 306px;;
          opacity: 1;
          margin: 0 0;
          z-index: 9999;
          background-color:rgb(51, 51, 51); 
          visibility: visible;
      }
    </style>
  </head>
  <body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>
    <script type="text/javascript" src="https://cloud9ide.github.io/emmet-core/emmet.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ext-emmet.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ext-language_tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://tertilecrystal.com/anime/anime.min.js"></script>

    <div class="modal fade" id="select_code_modal" tabindex="-1" aria-labelledby="select_code_modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="select_code_modalLabel">コードファイルの新規作成・読み込み</h5>
            <button type="button" class="btn-close" ash-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col text-center">
                  <input type="text" class="form-control" placeholder="過去に作成したファイル名を入力" id="file_key" value="<?php echo $file_name;?>">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-outline-primary" value="読み込み" id="load_file_btn" data-bs-dismiss="modal">
            <input type="button" class="btn btn-primary" value="新規作成" id="new_file_btn" data-bs-dismiss="modal">
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="start_up_modal" tabindex="-1" aria-labelledby="start_up_modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="start_up_modalLabel">TertileCode（ベータ版）へようこそ!!</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <img class="img-fluid" src="https://tertilecrystal.com/Image/Onboarding-bro.png" alt="welcome_img">
              </div>
              <div class="col">
                <div class="row">
                  <div class="col">
                    <h6>
                      TertileCodeはすべての年代の方が楽しくプログラミングをすることができるように開発されました。TertileCodeを使えば簡単に楽しくプログラミングをすることができます。
                      TertileCodeは会員登録不要で完全無料で利用することができます。また、無料のHTML講座も開発中です！<br>
                      <b>（このアプリケーションは開発途中のベータ版です。バグや修正点、ご意見などは
                        <a href="https://forms.gle/4GE1fuoHvhKhFAE67" target="_blank">こちらから</a>ご報告ください  ）
                      </b>
                    </h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <h3>Let's enjoy programming!!</h3>
                  </div>
                </div>
                <br>
                <!--
                <div class="row m-1">
                  <div class="col">
                    <input type="button" class="btn btn-primary w-100" value="チュートリアルを見る">
                  </div>
                </div>
                -->
                <div class="row m-1">
                  <div class="col">
                    <input type="button" class="btn btn-primary w-100" value="プログラミングを始める" id="start_pro">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <main class="container-fluid" style="height: 100vh; position: absolute;">
      <div class="row text-center" style="height: 7vh; color: white;font-family: 'Noto Sans JP', sans-serif; background-color: rgb(37, 38, 38);">
        <div class="col-2 text-start">
          <div class="row h-100">
            <div class="col-2 d-flex align-items-center">
              <input id="acd-check1" class="acd-check" type="checkbox">
              <label class="acd-label" for="acd-check1">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list menu_btn" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
              </label>
              <div class="acd-content">
                <div class="container-fluid h-100">
                  <div class="w-100 h-100" style="position: relative;">
                    <div class="row">
                      <div class="col">
                        <input id="acd-check1" class="acd-check" type="checkbox">
                        <label class="acd-label" for="acd-check1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list menu_btn" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                          </svg>
                        </label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col d-flex align-items-center">
                        <h2>TertileCode</h2>
                      </div>
                    </div>
                    <div class="row" style="margin-top: 3rem;">
                      <div class="col-2 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-journal-code" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8.646 5.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 8 8.646 6.354a.5.5 0 0 1 0-.708zm-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 8l1.647-1.646a.5.5 0 0 0 0-.708z"/>
                          <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                          <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                        </svg>
                      </div>
                      <div class="col d-flex align-items-center">
                        <a href="" style="text-decoration: none; color: white;"><h4 style="margin: 0 1rem;">Editor</h4></a>
                      </div>
                    </div>
                    <div class="row" style="margin-top: 1rem;">
                      <div class="col-2 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                          <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z"/>
                        </svg>
                      </div>
                      <div class="col d-flex align-items-center">
                        <a href="" style="text-decoration: none; color: white; cursor: not-allowed;"><h4 style="margin: 0 1rem;">Dashboard</h4></a>
                      </div>
                    </div>
                    <div class="w-100" style="bottom: 0; position: absolute; ">
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/" style="text-decoration: none; color: white;">
                            <h6>ホーム</h6>
                          </a>
                        </div>
                      </div>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/%e3%82%b5%e3%83%bc%e3%83%93%e3%82%b9/" style="text-decoration: none; color: white;">
                            <h6>サービス</h6>
                          </a>
                        </div>
                      </div>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/%e6%9b%b4%e6%96%b0%e6%83%85%e5%a0%b1/" style="text-decoration: none; color: white;">
                            <h6>ニュース</h6>
                          </a>
                        </div>
                      </div>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="" style="text-decoration: none; color: white;">
                            <h6>サポート</h6>
                          </a>
                        </div>
                      </div>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/%e3%81%8a%e5%95%8f%e3%81%84%e5%90%88%e3%82%8f%e3%81%9b/" style="text-decoration: none; color: white;">
                            <h6>お問い合わせ</h6>
                          </a>
                        </div>
                      </div>
                      <hr>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/%e5%88%a9%e7%94%a8%e8%a6%8f%e7%b4%84/" style="color: white;">
                            利用規約
                          </a>
                        </div>
                      </div>
                      <div class="row w-100" style="margin: 1rem;">
                        <div class="col d-flex align-items-center">
                          <a href="https://www.tertilecrystal.com/privacy-policy/" style="color: white;">
                            プライバシーポリシー
                          </a>
                        </div>
                      </div>
                      <hr>
                      <a href="https://storyset.com/idea">Illustration by Freepik Storyset</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col d-flex align-items-center">
              <h5>TertileCode</h5>
            </div>
          </div>
        </div>
        <div class="col-2 d-flex align-items-center">
          <h4 id="file_name">ファイル名：<?php echo $file_name; ?></h4>
        </div>
        <div class="col-1 icon" id="run_btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-caret-right" viewBox="0 0 16 16">
            <path d="M6 12.796V3.204L11.481 8 6 12.796zm.659.753l5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753z"/>
          </svg>
          <br>
          実行
        </div>
        <div class="col-1 icon" id="save_btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-arrow-up" viewBox="0 0 16 16">
            <path d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5z"/>
            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
          </svg>
          <br>
          保存
        </div>
        <div class="col-1 icon" data-bs-toggle="modal" data-bs-target="#select_code_modal">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-earmark-code" viewBox="0 0 16 16">
            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
            <path d="M8.646 6.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 9 8.646 7.354a.5.5 0 0 1 0-.708zm-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 9l1.647-1.646a.5.5 0 0 0 0-.708z"/>
          </svg>
          <br>
          ファイル
        </div>
        <div class="col-1 icon" style="cursor: not-allowed;">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
          </svg>
          <br>
          設定
        </div>
        <div class="col-4">
          <div class="w-100 m-2 text-start" style="border: 1px solid white;height: 5vh; border-radius: 5px;overflow-y: auto;" id="log">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col p-0" id="editor_col">
          <div id="editor" style="height: 93vh"></div>
        </div>

        <div class="col" style="font-family: 'Noto Sans JP', sans-serif;background-color: rgb(37, 38, 38);">
          <div class="row" style="height: 44vh;">
            <div class="col-2">
              <div class="row m-1">
                <div class="col">
                  <button type="button" class="btn btn-primary w-100" id="preview_btn">プレビュー</button>
                </div>
              </div>
              <div class="row m-1">
                <div class="col">
                  <button type="button" class="btn btn-primary w-100" id="learn_code_btn">使い方</button>
                </div>
              </div>
              <div class="row m-1">
                <div class="col">
                  <button type="button" class="btn btn-primary w-100" style="cursor: not-allowed;" id="learn_html_btn">HTML講座</button>
                </div>
              </div>
            </div>
            <div class="col-10"style="font-family: ''">
              <iframe src="<?php echo $file_name_result;?>" class="w-100 h-100" frameborder="0" id="web_result" style="border: 1px solid white; background-color: white;"></iframe>
            </div>
          </div>
          <hr style="color: white;">
          <div class="row" id="select_zone" >
            <div class="row m-1">
              <div class="col-3">
                <input type="button" class="btn btn-primary w-100" value="HTML">
              </div>
              <div class="col-9">
                <a href="https://forms.gle/4GE1fuoHvhKhFAE67" target="_blank">バグ・修正点・ご意見フォーム</a>
              </div>
              <!--
              <div class="col-4">
                <input type="text" class="form-control w-100" placeholder="検索">
              </div>
              <div class="col-2">
                <input type="button" class="btn btn-success w-100" value="検索">
              </div>
              -->
            </div>
            <div class="row" style="height: 40vh;">
              <div class="col-5">
                <div class="row h-100" >
                  <div class="col w-100" id="button_zone" style="height: 40vh; overflow-y: auto;color: white;"></div>
                </div>
              </div>
              <div class="col-7">
                <div class="row" id="create_zone" style="height: 40vh; overflow-y: auto;">
                  <div class="row" style="height: 10vh;" id="table"></div>
                  <div class="row" style="height: 10vh;">
                    <div class="col">
                      <p id="tag_ex" style="color: white;"></p>
                    </div>
                  </div>
                  
                  <div class="row" style="height: 15vh;">
                    <!--
                    <div class="col">
                      <div class="h-100" id="sub_editor">機能実装中</div>
                    </div>
                    -->
                    <div class="col m-2 p-0" id="preview_sub" style="background-color: white; border: 1px solid black;"></div>
                  </div>
                  
                  <div class="row" style="height: 5vh;">
                    <div class="col">
                      <input type="button" id="import_btn" class="btn btn-success w-100" value="挿入">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </body>

  <script>
    var start_up_modal = new bootstrap.Modal(document.getElementById('start_up_modal'), {
      keyboard: false
    })
    window.onload = function(){
      width = window.innerWidth;

      if(width <= 1000){
        window.location.href = "https://app.tertilecrystal.com/TertileCode/not_pc.html";
      }
    }

    //オブジェクト取得
    const start_pro = document.getElementById('start_pro');

    const new_file_btn = document.getElementById('new_file_btn');
    const load_file_btn = document.getElementById('load_file_btn');

    const run_btn = document.getElementById('run_btn');
    const save_btn = document.getElementById('save_btn');

    const preview_btn = document.getElementById('preview_btn');
    //const learn_html_btn = document.getElementById('learn_html_btn');
    const learn_code_btn = document.getElementById('learn_code_btn');

    const description = document.getElementById('description');

    const button_zone = document.getElementById('button_zone');
    const select_zone = document.getElementById('select_zone');
    const create_zone = document.getElementById('create_zone');

    const web_result = document.getElementById('web_result');
    const file_name_obj = document.getElementById('file_name');
    
    const table = document.getElementById('table');
    const tag_ex = document.getElementById('tag_ex');
    const preview_sub = document.getElementById('preview_sub');

    const import_btn = document.getElementById('import_btn');
    const cancel_btn = document.getElementById('cancel_btn');

    const log = document.getElementById('log');

    document.oncontextmenu = function () {return false;}

    //変数定義
    const tag_list = {};
    var count = 0;
    var id;
    var user_url;
    var url;
    var key;

    //エディター生成
    var editor = ace.edit("editor");
    editor.$blockScrolling = Infinity;
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/html");
    editor.setFontSize(16);
    editor.setOptions({
        enableEmmet: true,
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });

    write_log("TertileCodeにようこそ！");

    file_key = document.getElementById("file_key").value;
    if(file_key != "-"){      
      $.ajax({
        url: "create_code_file.php",
        type: "POST",
        data:{
            "text": "",
            "file_name" : file_key,
            "flag" : "false"
        }
      }).done(data => {
        data = data.split("\n");
        user_url = data[0];
  
        web_result.src = data[1];
        file_name_obj.innerHTML = user_url;

        console.log(data);
  
        for(var i = 2; i < data.length; i++){
          editor.insert(data[i] + "\n");
        }
  
        write_log("ファイルを読み込みました(" + user_url + ")");
      });
    }else{
      start_up_modal.show();
    }
    
    key = document.getElementById("file_key").value;

    function write_log(text){
      date = new Date();

      h5 = document.createElement('p');
      h5.innerHTML =  date.toLocaleString() + "&nbsp;:&nbsp;" + text;

      log.prepend(h5);
    }

    start_pro.addEventListener("click", function(){
      start_up_modal.hide();
    });

    learn_code_btn.addEventListener("click", function(){
      web_result.src = "https://docs.google.com/presentation/d/e/2PACX-1vRfmleOtReNQF9Huay8gVbCY5NBdzjyO5pMGOCwe4YO8SflDb6fnbh-dNoSKHq0Wamu2BzS303Y5wIk/embed?start=false&loop=false&delayms=3000";

      write_log("ビューウィンドウのURLを変更(TertileCodeの使い方)");
    });

    //learn_html_btn.addEventListener("click", function(){
      //web_result.src = "https://docs.google.com/presentation/d/e/2PACX-1vRaOw_FNZzcTpLOEpWv3TpQGgm6VbVF8QxqAoFV7aQdpRhE0ZkOEtOliOvdtfIlVggMzT0GjKsV5c09/embed?start=false&loop=false&delayms=60000";

      //write_log("ビューウィンドウのURLを変更(HTML講座)");
    //});

    preview_btn.addEventListener("click", function(){
      web_result.src = url;

      write_log("ビューウィンドウのURLを変更(プレビュー) + (" + key + ")");
    });

    run_btn.addEventListener("click", function(){
      if(key != "まだ-"){
        $('#web_result')[0].contentDocument.location.reload(true);
  
        write_log("コードを実行(" + key + ")");
      }else{
        swal({
          title: "エラー",
          text: "まだ-\nエラーコード：U06000"
        })
        write_log("保存に失敗しました。");
      }

    });

    save_btn.addEventListener("click", function(){
      if(key != "-"){
        $.ajax({
          url: "create_code_file.php",
          type: "POST",
          data:{
              "text": editor.getValue(),
              "file_name": user_url,
              "flag" : "write"
          }
        }).done(data => {
          write_log("保存しました(" + key + ")");
        });
      }else{
        swal({
          title: "エラー",
          text: "まだ-\nエラーコード：U06000"
        })
        write_log("保存に失敗しました。");
      }
    });

    new_file_btn.addEventListener("click", function(){
        $.ajax({
          url: "create_code_file.php",
          type: "POST",
          data:{
              "flag" : "true"
          }
        }).done(data => {
          data = data.split("\n");
          user_url = data[0];

          url = data[1];

          web_result.src = url;
          file_name_obj.innerHTML = user_url;

          editor.setValue("");

          console.log(data);

          write_log("新規ファイルの作成(" + user_url + ")");
          document.getElementById("file_key").value = user_url;
        });
      });

    
    load_file_btn.addEventListener("click", function(){
      $.ajax({
        url: "create_code_file.php",
        type: "POST",
        data:{
            "text": "",
            "flag" : "false",
            "file_name" : document.getElementById("file_key").value
        }
      }).done(data => {
        data = data.split("\n");
        user_url = data[0];

        web_result.src = data[1];
        file_name_obj.innerHTML = user_url;

        editor.setValue("");

        for(var i = 2; i < data.length; i++){
          editor.insert(data[i]);
        }

        write_log("ファイルを読み込みました(" + user_url + ")");
      });
    });

    //タグCSV取得
    const request = new XMLHttpRequest();
    request.addEventListener('load', (event) => {
      const response = event.target.responseText;
      tag_list = setup(response);
    });
    request.open('GET', './tag.csv', true);
    request.send();

    function setup(data) {
      const dataString = data.split('\n');
      dataString.forEach(datalist =>{
        datalist = datalist.split(',');
        tag_list[datalist[0]] = datalist;
      });

      //タグボタン生成
      for(var i = 1; i <= 25; i ++){
        object = '<div class="row"><div class="col-md w-100"><input type="button" class="btn btn-outline-light m-1 w-100" value="' + tag_list[i][1] + '" onclick="create(' + tag_list[i][0] + ') "></div></div>';

        button_zone.insertAdjacentHTML("beforeend", object);
      }
    }

    function create(req_id){
      id = req_id;

      pos = editor.getCursorPosition();
      column = pos['column'];
      blank = " ".repeat(column);

      while(table.firstChild){
        table.removeChild(table.firstChild);
      }
      while(preview_sub.firstChild){
        preview_sub.removeChild(preview_sub.firstChild);
      }

      object = "";
      count = 1;
      for(var i = 5; i <= 14; i ++){
        add_obj = "";
        text = tag_list[id][i];
        flag = true;

        text_list = text.split("*");
        if(text_list.length > 0){
          text = text_list[0]
        }

        switch(text){
          case "text":
            add_obj = '<input type="text" class="form-control" id="obj_' + count + '" placeholder="' + text_list[1] + '">';
            break;
          case "-":
            flag = false;
            break;
          default:
            add_obj = '<p style="color: white;">' + text + '</p>';
            break;
        }
        
        if(flag){
          object += '<div class="col text-center">' + add_obj + '</div>';
        }
      }
      
      table.insertAdjacentHTML("beforeend",  object);

      tag_ex.innerHTML = tag_list[id][4] + "<br>" + "ID：" + tag_list[id][0] + "<br>" + "Lang：" + tag_list[id][3];
      preview_sub.insertAdjacentHTML("beforeend", tag_list[id][16]);
    }

    import_btn.addEventListener("click", function(){
      basic_code = tag_list[id][15];
      code_list = basic_code.split("*");

      code = "";

      code_list.forEach(req_code => {
        add_code = "";
        switch(req_code){
          case "&n":
            add_code = "\n"
            break;

          default:
            if(!isNaN(req_code)){
              add_code = document.getElementById('obj_' + req_code).value;
            }else{
              add_code = req_code;
            }
        }

        code += add_code;
      });
      code += "\n";
      
      editor.insert(code);
    });
  </script>
</html>