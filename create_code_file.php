<?php
    $dsn = "mysql:host=localhost;dbname=tertilecrystal_code";
    $user_name = "tertilecrystal_code";
    $password = "h8et6ruj";

    $today = date("Y-m-d");   

    session_start();    

    try{
        $pdo = new PDO($dsn, $user_name, $password);
    }catch(PDOException $e){
        echo $e -> getMessage();
    }

    if(($_POST["flag"] == "false") || ($_POST["flag"] == "write")){
        $contents = $_POST["text"];
        $file_name = $_POST["file_name"];
    }else{
        $uploaddir = './CodeFile/';
        $folder_name_base = rand(1000, 9999999);
        $new_folder_name = password_hash(rand(1000, 9999999). "-tertilecode", PASSWORD_DEFAULT);
        $new_folder_name = str_replace("/", "", $new_folder_name);
        $new_folder_name = str_replace(".", "", $new_folder_name);

        try{    
            $stmt = $pdo->prepare("INSERT INTO code (key_name, url, c_date, u_date, d_date, size) VALUES(:key_name, :url, :c_date, :u_date, :d_date, :size)");
            $stmt->bindValue(":key_name", $folder_name_base, PDO::PARAM_INT);
            $stmt->bindValue(":url", $new_folder_name, PDO::PARAM_STR);
            $stmt->bindValue(":c_date", $today, PDO::PARAM_STR);
            $stmt->bindValue(":u_date", $today, PDO::PARAM_STR);
            $stmt->bindValue(":d_date", date("Y-m-d",strtotime("+7 day")), PDO::PARAM_STR);
            $stmt->bindValue(":size", 0, PDO::PARAM_INT);
            $stmt->execute();
            
        }catch(PDOException $e){
            echo $e -> getMessage();;
        }

        mkdir($uploaddir. $new_folder_name);

        $contents = "";
        $file_handle = fopen($uploaddir. $new_folder_name. "/". "index.html", "w");
        fwrite($file_handle, $contents);   

        $file_name = $folder_name_base;
    }

    echo $file_name. "\n";

    $_SESSION['key'] = $file_name;

    $file_name_result = "";

    try{
        $sql = "SELECT url FROM code WHERE key_name='". $file_name ."'";
        $res = $pdo->query($sql);
    
        foreach($res as $value){
            $file_name_result = "./CodeFile/". $value['url']. "/index.html";
        }

        
        echo $file_name_result. "\n";

        echo file_get_contents($file_name_result);

        if($_POST["flag"] == "write"){   
            $file_handle = fopen($file_name_result, "w");
            fwrite($file_handle, $contents);   
            $sql = "UPDATE code SET u_date = '". $today ."' WHERE key_name='". $file_name ."'";
            $res = $pdo->query($sql);
        }
    }catch(PDOException $e){

    }
?>