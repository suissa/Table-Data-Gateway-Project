<?php
	
	// @author R'Santiago 
	
	define('APP_NAME', ''); //defina aqui o nome do projeto
	define('SITE_URL', $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.APP_NAME."/"); // insira aqui o PATH real do projeto
	define('SITE_ROOT', $_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.APP_NAME); //insira aqui o PATH do server.


	
	//url destino para o CRUD 
	define('RECORD', 'app/util/record.php');
	
	//url de destino da Conexao
	define('CONEXAO', 'connection.php');
	
	
	//modo de desenvolvimento (p = production , d = development)
	define('MODE', 'd');

	
	/*
		*
		*	CONFIGURAÇÕES DO DATABASE
	*/

	if(MODE == 'd'):
			define('HOST', 'localhost'); //host ex. localhost
			define('USER', 'root');// usuario do banco ex. root
			define('PASS', 'sktbording'); // senha
			define('DB', 'n10'); // banco do projeto
	else:
			define('HOST', "localhost"); //host ex. localhost
			define('USER', '');// usuario do banco ex. root
			define('PASS', ''); // senha
			define('DB', ''); // banco do projeto
	endif;

