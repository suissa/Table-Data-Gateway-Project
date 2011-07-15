<?php
			include 'data_storage.php';
			class Connection{
						
						public function __construct(){
								
						}
						public function connect($mode){
				
									$con = mysql_connect(HOST, USER, PASS);
							
									if($con):
											mysql_select_db(DB);	
									else:
											die ('erro de Conex&atilde;o!');
									endif;
						}
						
			}

