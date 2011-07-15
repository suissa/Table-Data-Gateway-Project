<?php

			/*  
			  * pagina de exemplo , para fim de teste. 
			*/ 
			
			require_once('TDG.php');

			$teste = TDG::selectAll('usuario'); // insert table name HERE.
			
			while($testes = mysql_fetch_array($teste)):
					echo "${testes['login']} = ${testes['senha']} <br>"; // insert the column table name.
			endwhile;
