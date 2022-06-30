<?php
###################################################
#  Este script, quando recebe certas queryes,     #
#  envia email para o endereço configurado aqui.  #
#  Ele pode enviar: IPs, geolocalização e mensa-  #
#  gens para email. Sim, se precisar colocar um   #
#  formulário de contato que realmente envia em-  #
#  ails, pode fazer seu formulário conversar com  #
#  este script.                                   #
###################################################

/* Obtendo data e hora */
$current_time = time();
$date=date('Y-m-d H:i:s', $current_time);


/* customize estes dados */
define("to_emailaddress","seuemail@aqui.com");
define("from_emailaddress","emaildeseuservidor@email.com");
define("fakecompany","Nome de uma empresa fictícia");


/* função que envia email */
function custom_mailer($to_address,$from_address,$bcc,$subject,$message){
	$headers = "From: $from_address\r\nX-Mailer: php\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	if($bcc!=""){
		$headers .= "Bcc: $bcc\r\n";
	}
	$returnst=false;


	if(mail("$to_address", "$subject", "$message", "$headers")){
		$returnst=true;
	}
	return($returnst);

}

/* uma array que receberá as respostas a serem enviadas a formulários */
$toreturn=Array();
$success=false;
$tag="";

/* A query tag pode ser qualquer coisa que você queira enviar, seja um IP ou geolocalização *?
if(isset($_GET['tag'])){
	$tag=$_GET['tag'];
}

/* a query action , recebe alguma ação que você quiser que o script execute */
/* pode ser:
[contact] para receber mensagens de um formulário,
[newsletter] para receber um nome e email,
[track] para enviar um IP
e [locale] para enviar localização */

if(isset($_GET['action'])){

	$action=$_GET['action'];

	switch($action){
		case "contact":
		if(sendcontact()){
			$success=true;		
		}
		break;
		case "newsletter":
		if(sendnews()){
			$success=true;
		}
		break;
		case "track":
			custom_mailer("davidmarquesbv@gmail.com","no-reply@octocm.com","","Página visitada em ".$date,"Visitaram o site em ".$date."<br>".$tag);
		break;
		case "locale":
			custom_mailer("davidmarquesbv@gmail.com","no-reply@octocm.com","","Página visitada em ".$date,"Local Obtido em ".$date."<br><a href='https://maps.google.com/maps?q=loc:".$tag."'>https://maps.google.com/maps?q=loc:".$tag."</a>");
		break;
	}
	$toreturn['success']=$success;
}
/* exibe uma resposta json que poderá ser consumida por algum recurso ajax */
/* esta resposta, só é exibida quando a ação NÃO for track */

if($action!="track" && $action!=""){
echo json_encode($toreturn);
}



/* função para lidar com envio de emails */
function sendcontact(){
	if( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']) && $_POST['name'] != "" && $_POST['email'] != "" && $_POST['subject'] != "" && $_POST['message'] != ""){
		$message="Contato enviado pelo site<br> Nome: ".$_POST['name']."<br> Email: ".$_POST['email']."<br> Mensagem:<hr>".$_POST['message'];

		if(custom_mailer(to_emailaddress,from_emailaddress,"","Contato pelo site - ".$_POST['subject'],$message)){
			custom_mailer($_POST['email'],from_emailaddress,"","Obrigado por entrar em contato 😃","<h1>Obrigado, ".$_POST['name']."! </h1>Você é importante para nós. Entraremos em contato assim que possível.");
			return(true);
		}else{
			return(false);
		}
	}else{
		return(false);
	}
}

function sendnews(){
	if( isset($_POST['email']) && $_POST['email']!="" ){

		if(custom_mailer(to_emailaddress,from_emailaddress,"","Newsletter preenchido - ".$_POST['email'],"Assinou newsletter")){
			custom_mailer($_POST['email'],from_emailaddress,"","Newsletter ".fakecompany." 😃","<h1>Obrigado!</h1>Você agora está incluído em nossa newsletter.");
			return(true);
		}else{
			return(false);
		}
	}else{
		return(false);	
	}
}
