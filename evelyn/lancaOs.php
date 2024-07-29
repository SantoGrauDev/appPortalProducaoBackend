<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header("Access-Control-Allow-Origin:* ");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");


$db_conn= mysqli_connect("localhost","root", "", "reactphp");
if($db_conn===false)
{
  die("ERROR: Could Not Connect".mysqli_connect_error());
}

$method = $_SERVER['REQUEST_METHOD'];
//echo "test----".$method; die;
switch($method)
{

    case "POST":
      if(isset($_FILES['pfile']))
      {      
        $vendedor= $_POST['vendedor'];
        $OS= $_POST['OS'];
        $LENTE= $_POST['LENTE'];
        $TIPO_LENTE= $_POST['TIPO_LENTE'];
        $INDICE= $_POST['INDICE'];
        $VALOR= $_POST['VALOR'];
        $GRADE= $_POST['GRADE'];
        $DNPOE= $_POST['DNPOE'];
        $DNPOD= $_POST['DNPOD'];
        $DP= $_POST['DP'];
        $COMENTARIO= $_POST['COMENTARIO'];
        $pfile= time().$_FILES['pfile']['name'];
        $pfile_temp= $_FILES['pfile']['tmp_name'];
        $destination= 'img'."/".$pfile;

    
           $result= mysqli_query($db_conn,"INSERT INTO tbl_product (vendedor, OS,pfile, LENTE, TIPO_LENTE,INDICE, VALOR, GRADE, DNPOE, DNPOD, DP, COMENTARIO)
        VALUES('$vendedor','$OS','$pfile', '$LENTE', '$TIPO_LENTE', '$INDICE', '$VALOR', '$GRADE', '$DNPOE', '$DNPOD', '$DP', '$COMENTARIO')");

        if($result)
        { 
          move_uploaded_file($pfile_temp, $destination);
          echo json_encode(["success"=>true]);
           return;
        } else{
          echo json_encode(["success"=>true]);
           return;
        }

      } else{
        echo json_encode(["success"=>false]);
        return;
      }
        
    break;

    case "DELETE":
           
    break;

          

}


?>