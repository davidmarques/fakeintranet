<?php
if(isset($_POST['logfield']) || isset($_POST['passfield']) ){

  $message="<p class='rederror'>Login ou senha incorretos</p>";

}else{
  $message="";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Intranet OctoSys</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <style>
    body{
      background-color: #f8f8f8;
    }
    .rederror{
      color: #FF0000;
      font-weight: bolder;
      text-align: center;
    }
    .octosys-header{
      background-image: url("/intranet/people-working.jpeg");
      background-position: center center;
      background-size: cover;
    }
    .whiteback50{
      background-color: #ffffffce;
    }
    .clicableradio{
      margin-top: 5px;
      margin-bottom: 5px;
      display: block;
      padding: 15px;
      background-color: #f8f8f8;
    }
    .largeform{
      width: 100%;
    }
    small{
      text-align: right;
      font-style: italic;
      margin-bottom: 8px;
      margin-top: -4px !important;
    }
    .footerdemo{
      background-color: #eaeaea;
      line-height: 100px;
      text-align: center;
    }
    .margedbottom{
      margin-bottom: 100px;
    }
    .heigthfull{
      min-height: 100%;
    }
    label {
      margin-bottom: -0.5rem !important;
    }
    .hiddenpic{
      z-index: -1;
      margin-bottom: -10px;
      width: 0;
      height: 0;
    }
    .spaced-cont{
      margin-bottom: 20%;
      margin-top: 20%;
    }
  </style>
  <div class="octosys-header">
    <div class="jumbotron text-center whiteback50">
      <h1>OctoSys</h1>
      <p>Sistema de gestão integrada</p> 
    </div>
  </div>
  
  <div class="container-fluid spaced-cont">
    <div class="row justify-content-md-center">

      <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4">

        <form method="post">


          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Faça login para acessar</h5>

              <?php echo $message; ?>

              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 ">
                 <p> Login:</p>
               </div>
               <div class="col-sm-12 col-md-12 col-lg-6 ">
                <p><input type="text" name="logfield" required="true"/></p>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-6 ">
               <p> Senha:</p>
             </div>
             <div class="col-sm-12 col-md-12 col-lg-6 ">
               <p> <input type="password" name="passfield" required="true"/></p>
             </div>
           </div>


           <button type="submit" class="btn btn-primary btn-lg btn-block">Acessar</button>
         </div>
       </div>

     </form>


   </div>

 </div>
</div>
<footer class="footerdemo">
  &copy; OctoCM | 2010-2022
</footer>
</body>
</html>
