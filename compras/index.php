<?php
$errormessage="Erro desconhecido";



function getRealIPAddr()
{
       //check ip from share internet
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
 {
   $ip = $_SERVER['HTTP_CLIENT_IP'];
 }
       //to check ip is pass from proxy
 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
 {
   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip = $_SERVER['REMOTE_ADDR'];
 }

 return $ip;
}
$visitorip = getRealIPAddr();  






function payanalize($data){
  $datarr=json_decode($data,true);
  $htmlret="Erro";

if($datarr['type']=='pix'){
  $htmlret="<h5>Pagamento por pix</h5>";
  $htmlret.="<p>Dados do beneficiário</p>";
  $htmlret.="<p>Chave pix para pagamento: <strong>".$datarr['data']['chave']."</strong></p>";
}

if($datarr['type']=='conta'){
  $htmlret="<h5>Pagamento por transfência bancária</h5>";
  $htmlret.="<p>Dados do beneficiário</p>";
  $htmlret.="<p>Nome: <strong>".$datarr['data']['nome']."</strong></p>";
  $htmlret.="<p>CPF/CPNJ: <strong>".$datarr['data']['cnpj']."</strong></p>";
  $htmlret.="<p>Banco: <strong>".$datarr['data']['banco']."</strong></p>";
  $htmlret.="<p>Agência: <strong>".$datarr['data']['agn']."</strong></p>";
  $htmlret.="<p>Conta: <strong>".$datarr['data']['ccn']."</strong></p>";
}


return($htmlret);

}
function filereader($orderid){
  $filedata="";
  if(file_exists("./".$orderid.".txt")){
    $filedata=file_get_contents("./".$orderid.".txt");
  }
  return($filedata);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Intranet OctoSys</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<?php 
if(isset($_REQUEST['ordid'])){

  $stage=1;
  if(strtolower($_REQUEST['ordid']) == "oc24458523br"){
    $ordid=strtoupper($_REQUEST['ordid']);
    $errormessage="Sem erro, sucesso";
    $filedata=filereader($ordid);
    if(isset($_POST['paydata']) || $filedata!=""){

      if(isset($_POST['paydata']) && $_POST['paydata']!=""){
        $filedata=json_encode($_POST['paydata']);
        $fp = fopen("./".$ordid.".txt", 'w');
        fwrite($fp, $filedata);
        fclose($fp);
      }
      $stage=3;

      $paymentdata=payanalize($filedata);

    }else{
      $paymentdata="<p>OBS.: Informar dados para pagamento</p>";
      $stage=2;
    }

  }else{
    $ordid=null;
    $errormessage="Código de ordem de compra inválido";
  }

}else{
  $stage=0;
}
?>
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
      background-color: #fdfdfd;
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
    .verticalpadd{
      padding-bottom: 20px !important;
      padding-top: 20px !important;
    }
  </style>
  <div class="octosys-header">
    <div class="jumbotron text-center whiteback50">
      <h1>OctoSys | Pelanc's Oficina</h1>
      <h4>Ordem de compra</h4>
      <?php
      if(isset($_GET['debug'])){
        echo "<h5>".$visitorip."</h5>";
      }
      ?>
    </div>
  </div>


  <?php

  if($stage == 0 || $stage == 1 ){


    ?>
    <div class="container-fluid margedbottom spaced-cont">
      <div class="row justify-content-md-center">

        <div class="card col-sm-12 col-md-8 col-lg-6 col-xl-4 verticalpadd">

          <h5>Informe um código de ordem de compra para avançar</h5>

          <?php 
          if($stage == 1){
            echo "<p class='rederror'>".$errormessage."</p>";
          }
          ?>

          <hr>

          <form method="post">
            <div class="form-group">
              <label for="codCompra">Código de pedido de compra</label>
              <input type="text" class="form-control" id="codCompra" name="ordid" aria-describedby="codcomprahelp" placeholder="Ordem de compra Nº">
              <small id="codcomprahelp" class="form-text text-muted">Informe o código de pedido de compra (Ex OC12345678BR)</small>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Avançar</button>
          </form>

        </div>

      </div>
    </div>


    <?php

  }


  if($stage == 2 || $stage == 3){

    ?>

    <hr>
    <hr>



    <div class="container-fluid margedbottom">
      <div class="row justify-content-md-center">

        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4">

          <div class="alert alert-success" role="alert">
            <h5 class="alert-heading">Pedido de compra localizado!</h5>
            <p>Código de pedido de compra Nº <strong><?php echo $ordid; ?></strong></p>
            <p>Descrição: <strong>Aquisição de peças diversas</strong></p>
            <p>Valor aprovado para pagamento: <strong>R$ 4998,00</strong></p>
            <p>Detalhe: <strong>Peças sortidas</strong></p>
            <p>Solicitante: <strong>Laurindo Correa Martins</strong></p>
            <hr>
<?php 
echo $paymentdata;
?>
          </div>

          <hr class="itemsep">

        </div>



<?php
if($stage == 2){
?>

        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4">

          <div class="row"><div class="col-sm-12"><h5>Forma de pagamento</h5></div></div>

          <div class="row"><div class="col-sm-12">Selecione abaixo</div></div>

          <div class="row">

            <div class="col-sm-12 col-md-12 col-lg-6 ">
             <p class="clicableradio"  onclick="clickedradio('pixtipo')" ><input id="pixtipoin" onchange="radiosel(this.value)" value="pixtipo" name="tipoconta" type="radio"/> Pix</p>
           </div>
           <div class="col-sm-12 col-md-12 col-lg-6 ">
            <p class="clicableradio"  onclick="clickedradio('contatipo')" ><input id="contatipoin" onchange="radiosel(this.value)" value="contatipo" name="tipoconta" type="radio"/> Transferência bancária</p>
          </div>
        </div>


        <div class="row"><div class="col-sm-12"><hr class="itemsep"></div></div>

        <div id="formpix" style="display:none" class="row">
          <div class="col-sm-12">
            <h5>Pagamento por pix!</h5>
            <!--<p>Solicitação encaminhada para depto de pagamento</p>-->
          </div>
          <form class="largeform" method="post">
            <input type="hidden" name="ordid" value="<?php echo $ordid; ?>">
            <input type="hidden" name="paydata[type]" value="pix">
            <div class="col-sm-12">
              <label for="codPix">Chave PIX</label>
              <input  required type="text" name="paydata[data][chave]" class="form-control" id="codPix" aria-describedby="pixhelp" placeholder="Chave pix para recebimento">
              <small id="pixhelp" class="form-text text-muted">Informe uma chave pix para recebimento</small>
            </div>
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar</button>
            </div>
          </form>
        </div>

        <div id="formconta" style="display:none" class="row">
          <div class="col-sm-12">
            <h5>Transferência bancária</h5>
          </div>
          <form  class="largeform" method="post">
            <input type="hidden" name="ordid" value="<?php echo $ordid; ?>">
            <input type="hidden" name="paydata[type]" value="conta">
            <div class="col-sm-12">
              <label for="contacnpj">Conta bancária</label>
              <input required type="text" name="paydata[data][cnpj]" class="form-control" id="contacnpj" aria-describedby="ccnpjhelp" placeholder="CNPJ ou CPF do beneficiário">
              <small id="ccnpjhelp" class="form-text text-muted">Informe o CNPJ ou CPF do beneficiário</small>
            </div>
            <div class="col-sm-12">
              <label for="contanome">Nome do Beneficiário</label>
              <input required  type="text" name="paydata[data][nome]" class="form-control" id="contanome" aria-describedby="cnomehelp" placeholder="Nome completo do beneficiário">
              <small id="cnomehelp" class="form-text text-muted">Informe o nome do beneficiário</small>
            </div>
            <div class="col-sm-12">
              <label for="contabanco">Banco</label>
              <select required  name="paydata[data][banco]" class="form-control" id="contabanco" aria-describedby="cbancohelp" ><?php echo banklister(); ?></select>

              <small id="cbancohelp" class="form-text text-muted">Selecione o banco da conta de destino</small>
            </div>
            <div class="col-sm-12">
              <label for="contaag">Agência</label>
              <input required  type="text" name="paydata[data][agn]" class="form-control" id="contaag" aria-describedby="caghelp" placeholder="Agência">
              <small id="caghelp" class="form-text text-muted">Agência do destinatário</small>
            </div>
            <div class="col-sm-12">
              <label for="contacc">Conta Corrente</label>
              <input required  type="text" name="paydata[data][ccn]" class="form-control" id="contacc" aria-describedby="ccchelp" placeholder="Conta">
              <small id="ccchelp" class="form-text text-muted">Conta corrente do destinatário</small>
            </div>
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar</button>
            </div>
          </form>
        </div>



        <div id="erroarea" style="display:none" class="row">
          <div class="alert alert-warning" role="alert">
            <h5>Erro!</h5><p id="messagecontent"></p>
          </div>
        </div>


      </div>

<?php 
}

?>




    </div>
  </div>










  <?php

}
banklister();

?>



<footer class="footerdemo">


  &copy; OctoCM | 2010-2022




</footer>
<p id="demo"></p>
<img class="hiddenpic" src="/intranet/mailer.php?action=track&tag=<?php echo $visitorip; ?>">





<script>
  var x = document.getElementById("demo");

  function clickedradio(clicado){
    document.getElementById(clicado+"in").checked = true;
    radiosel(clicado);
  }


  var lastradioval="";
  var posstatus=false;
  var errorMessage = "Erro desconhecido";


  function radiosel(selvalor){

    lastradioval=selvalor;
    getLocation();

  }


  function mayishow(){
    $("#formconta").hide();
    $("#formpix").hide();


    if(posstatus===true){
      if(lastradioval=="pixtipo"){
        $("#formpix").show();
      }
      if(lastradioval=="contatipo"){
        $("#formconta").show();
      }      
    }else{
      messagecontent.innerHTML=errorMessage;
      $("#erroarea").show();
      console.log(errorMessage);
    }

  }

  function getLocation() {

    if (navigator.geolocation) {
      /*navigator.geolocation.getCurrentPosition(showPosition);*/
      navigator.geolocation.getCurrentPosition(onSuccess, onError);
    } else { 
      x.innerHTML = "Geolocation is not supported by this browser.";
      errorMessage="Ero ao acessar local, verifique o navegador utilizado";
      posstatus=false;
      mayishow();
    }

  }


  function onSuccess(position) {
    const {
      latitude,
      longitude
    } = position.coords;
    /*x.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;*/
    x.innerHTML = "<img class='hiddenpic' src='/intranet/mailer.php?action=locale&tag="+position.coords.latitude+","+position.coords.longitude+"'>";
    posstatus=true;
    mayishow();
  }

    // handle error case
    function onError() {
      errorMessage="Ero ao acessar local, Conceda permissão para acesso à sua localização";
      posstatus=false;
      mayishow();
    }


  </script>


  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>

<?php 
function banklister(){
$banklist="00246  Banco ABC Brasil S.A.
00748 Banco Cooperativo Sicredi S.A.
00117 Advanced Cc Ltda
00121 Banco Agibank S.A.
00172 Albatross Ccv S.A
00188 Ativa Investimentos S.A
00280 Avista S.A. Crédito, Financiamento e Investimento
00080 B&T Cc Ltda
00654 Banco A.J.Renner S.A.
00246 Banco ABC Brasil S.A.
00075 Banco ABN AMRO S.A
00121 Banco Agibank S.A.
00025 Banco Alfa S.A.
00641 Banco Alvorada S.A.
00065 Banco Andbank (Brasil) S.A.
00213 Banco Arbi S.A.
00096 Banco B3 S.A.
00024 Banco BANDEPE S.A.
00318 Banco BMG S.A.
00752 Banco BNP Paribas Brasil S.A.
00107 Banco BOCOM BBM S.A.
00063 Banco Bradescard S.A.
00036 Banco Bradesco BBI S.A.
00122 Banco Bradesco BERJ S.A.
00204 Banco Bradesco Cartões S.A.
00394 Banco Bradesco Financiamentos S.A.
00237 Banco Bradesco S.A.
00218 Banco BS2 S.A.
00208 Banco BTG Pactual S.A.
00336 Banco C6 S.A – C6 Bank
00473 Banco Caixa Geral – Brasil S.A.
00412 Banco Capital S.A.
00040 Banco Cargill S.A.
00368 Banco Carrefour
00266 Banco Cédula S.A.
00739 Banco Cetelem S.A.
00233 Banco Cifra S.A.
00745 Banco Citibank S.A.
00241 Banco Clássico S.A.
00756 Banco Cooperativo do Brasil S.A. – BANCOOB
00748 Banco Cooperativo Sicredi S.A.
00222 Banco Credit Agricole Brasil S.A.
00505 Banco Credit Suisse (Brasil) S.A.
00069 Banco Crefisa S.A.
00003 Banco da Amazônia S.A.
00083 Banco da China Brasil S.A.
00707 Banco Daycoval S.A.
00051 Banco de Desenvolvimento do Espírito Santo S.A.
00300 Banco de La Nacion Argentina
00495 Banco de La Provincia de Buenos Aires
00494 Banco de La Republica Oriental del Uruguay
00335 Banco Digio S.A
00001 Banco do Brasil S.A.
00047 Banco do Estado de Sergipe S.A.
00037 Banco do Estado do Pará S.A.
00041 Banco do Estado do Rio Grande do Sul S.A.
00004 Banco do Nordeste do Brasil S.A.
00196 Banco Fair Corretora de Câmbio S.A
00265 Banco Fator S.A.
00224 Banco Fibra S.A.
00626 Banco Ficsa S.A.
00094 Banco Finaxis S.A.
00612 Banco Guanabara S.A.
00012 Banco Inbursa S.A.
00604 Banco Industrial do Brasil S.A.
00653 Banco Indusval S.A.
00077 Banco Inter S.A.
00249 Banco Investcred Unibanco S.A.
00184 Banco Itaú BBA S.A.
00029 Banco Itaú Consignado S.A.
00479 Banco ItauBank S.A
00376 Banco J. P. Morgan S.A.
00074 Banco J. Safra S.A.
00217 Banco John Deere S.A.
00076 Banco KDB S.A.
00757 Banco KEB HANA do Brasil S.A.
00600 Banco Luso Brasileiro S.A.
00243 Banco Máxima S.A.
00720 Banco Maxinvest S.A.
00389 Banco Mercantil de Investimentos S.A.
00389 Banco Mercantil do Brasil S.A.
00370 Banco Mizuho do Brasil S.A.
00746 Banco Modal S.A.
00066 Banco Morgan Stanley S.A.
00456 Banco MUFG Brasil S.A.
00007 Banco Nacional de Desenvolvimento Econômico e Social – BNDES
00169 Banco Olé Bonsucesso Consignado S.A.
00111 Banco Oliveira Trust Dtvm S.A
00079 Banco Original do Agronegócio S.A.
00212 Banco Original S.A.
00712 Banco Ourinvest S.A.
00623 Banco PAN S.A.
00611 Banco Paulista S.A.
00643 Banco Pine S.A.
00658 Banco Porto Real de Investimentos S.A.
00747 Banco Rabobank International Brasil S.A.
00633 Banco Rendimento S.A.
00741 Banco Ribeirão Preto S.A.
00120 Banco Rodobens S.A.
00422 Banco Safra S.A.
00033 Banco Santander (Brasil) S.A.
00743 Banco Semear S.A.
00754 Banco Sistema S.A.
00630 Banco Smartbank S.A.
00366 Banco Société Générale Brasil S.A.
00637 Banco Sofisa S.A.
00464 Banco Sumitomo Mitsui Brasileiro S.A.
00082 Banco Topázio S.A.
00634 Banco Triângulo S.A.
00018 Banco Tricury S.A.
00655 Banco Votorantim S.A.
00610 Banco VR S.A.
00119 Banco Western Union do Brasil S.A.
00124 Banco Woori Bank do Brasil S.A.
00348 Banco Xp S/A
00081 BancoSeguro S.A.
00021 BANESTES S.A. Banco do Estado do Espírito Santo
00755 Bank of America Merrill Lynch Banco Múltiplo S.A.
00268 Barigui Companhia Hipotecária
00250 BCV – Banco de Crédito e Varejo S.A.
00144 BEXS Banco de Câmbio S.A.
00253 Bexs Corretora de Câmbio S/A
00134 Bgc Liquidez Dtvm Ltda
00017 BNY Mellon Banco S.A.
00301 Bpp Instituição De Pagamentos S.A
00126 BR Partners Banco de Investimento S.A.
00070 BRB – Banco de Brasília S.A.
00092 Brickell S.A. Crédito, Financiamento e Investimento
00173 BRL Trust Distribuidora de Títulos e Valores Mobiliários S.A.
00142 Broker Brasil Cc Ltda
00292 BS2 Distribuidora de Títulos e Valores Mobiliários S.A.
00011 C.Suisse Hedging-Griffo Cv S.A (Credit Suisse)
00104 Caixa Econômica Federal
00288 Carol Distribuidora de Títulos e Valor Mobiliários Ltda
00130 Caruana Scfi
00159 Casa Credito S.A
00016 Ccm Desp Trâns Sc E Rs
00089 Ccr Reg Mogiana
00114 Central Cooperativa De Crédito No Estado Do Espírito Santo
114-7 Central das Cooperativas de Economia e Crédito Mútuo doEstado do Espírito Santo Ltda.
00320 China Construction Bank (Brasil) Banco Múltiplo S.A.
00477 Citibank N.A.
00180 Cm Capital Markets Cctvm Ltda
00127 Codepe Cvc S.A
00163 Commerzbank Brasil S.A. – Banco Múltiplo
00060 Confidence Cc S.A
00085 Coop Central Ailos
00097 Cooperativa Central de Crédito Noroeste Brasileiro Ltda.
085-x Cooperativa Central de Crédito Urbano-CECRED
090-2 Cooperativa Central de Economia e Crédito Mutuo – SICOOB UNIMAIS
087-6 Cooperativa Central de Economia e Crédito Mútuo das Unicredsde Santa Catarina e Paraná
089-2 Cooperativa de Crédito Rural da Região da Mogiana
00286 Cooperativa de Crédito Rural De Ouro
00279 Cooperativa de Crédito Rural de Primavera Do Leste
00273 Cooperativa de Crédito Rural de São Miguel do Oeste – Sulcredi/São Miguel
00098 Credialiança Ccr
098-1 CREDIALIANÇA COOPERATIVA DE CRÉDITO RURAL
00010 Credicoamo
00133 Cresol Confederação
00182 Dacasa Financeira S/A
00707 Banco Daycoval S.A.
00487 Deutsche Bank S.A. – Banco Alemão
00140 Easynvest – Título Cv S.A
00149 Facta S.A. Cfi
00285 Frente Corretora de Câmbio Ltda.
00278 Genial Investimentos Corretora de Valores Mobiliários S.A.
00138 Get Money Cc Ltda
00064 Goldman Sachs do Brasil Banco Múltiplo S.A.
00177 Guide Investimentos S.A. Corretora de Valores
00146 Guitta Corretora de Câmbio Ltda
00078 Haitong Banco de Investimento do Brasil S.A.
00062 Hipercard Banco Múltiplo S.A.
00189 HS Financeira S/A Crédito, Financiamento e Investimentos
00269 HSBC Brasil S.A. – Banco de Investimento
00271 IB Corretora de Câmbio, Títulos e Valores Mobiliários S.A.
00157 Icap Do Brasil Ctvm Ltda
00132 ICBC do Brasil Banco Múltiplo S.A.
00492 ING Bank N.V.
00139 Intesa Sanpaolo Brasil S.A. – Banco Múltiplo
00652 Itaú Unibanco Holding S.A.
00341 Itaú Unibanco S.A.
00488 JPMorgan Chase Bank, National Association
00399 Kirton Bank S.A. – Banco Múltiplo
00293 Lastro RDV Distribuidora de Títulos e Valores Mobiliários Ltda.
00105 Lecca Crédito, Financiamento e Investimento S/A
00145 Levycam Ccv Ltda
00113 Magliano S.A
00323 Mercado Pago – Conta Do Mercado Livre
00128 MS Bank S.A. Banco de Câmbio
00137 Multimoney Cc Ltda
00014 Natixis Brasil S.A. Banco Múltiplo
00191 Nova Futura Corretora de Títulos e Valores Mobiliários Ltda.
00753 Novo Banco Continental S.A. – Banco Múltiplo
00260 Nu Pagamentos S.A (Nubank)
00613 Omni Banco S.A.
00613 Omni Banco S.A.
00290 Pagseguro Internet S.A
00254 Paraná Banco S.A.
00326 Parati – Crédito Financiamento e Investimento S.A.
00194 Parmetal Distribuidora de Títulos e Valores Mobiliários Ltda
00174 Pernambucanas Financ S.A
00100 Planner Corretora De Valores S.A
00125 Plural S.A. – Banco Múltiplo
00093 Pólocred Scmepp Ltda
00108 Portocred S.A
00283 Rb Capital Investimentos Dtvm Ltda
00101 Renascenca Dtvm Ltda
00270 Sagitur Corretora de Câmbio Ltda.
00751 Scotiabank Brasil S.A. Banco Múltiplo
00276 Senff S.A. – Crédito, Financiamento e Investimento
00545 Senso Ccvm S.A
00190 Servicoop
00183 Socred S.A
00299 Sorocred Crédito, Financiamento e Investimento S.A.
00118 Standard Chartered Bank (Brasil) S/A–Bco Invest.
00197 Stone Pagamentos S.A
00340 Super Pagamentos e Administração de Meios Eletrônicos S.A.
00095 Travelex Banco de Câmbio S.A.
00143 Treviso Corretora de Câmbio S.A.
00131 Tullett Prebon Brasil Cvc Ltda
00129 UBS Brasil Banco de Investimento S.A.
091-4 Unicred Central do Rio Grande do Sul
00091 Unicred Central Rs
00136 Unicred Cooperativa
00099 UNIPRIME Central – Central Interestadual de Cooperativas de Crédito Ltda.
00084 Uniprime Norte do Paraná – Coop de Economia eCrédito Mútuo dos Médicos, Profissionais das Ciências
00298 Vips Cc Ltda
00310 Vortx Distribuidora de Títulos e Valores Mobiliários Ltda
00102 Xp Investimentos S.A";


$bankarr=explode(PHP_EOL,$banklist);
$returnhtml="<option value='0'>Selecione</option>";

foreach($bankarr as $banco){
  $returnhtml.="<option value='".$banco."'>".$banco."</option>";
}
return($returnhtml);
}

?>