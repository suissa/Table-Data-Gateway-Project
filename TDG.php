<?php
			
			include_once 'connection.php';
			class TDG{
						
					private static $select = "";
					private static $fields = "";
					private static $update = "";
					private static $from = "";
					private static $join = "";
					private static $on = "";
					private static $table = "";
					private static $where = "";
					private static $order = "";
					private static $limit = "";
					private static $group = "";
					private static $delete = "";

					
					public function __construct(){
							
					}
					
					public function lastID($table){
								$lasti = mysql_query("select last_insert_id() from ".$table);
								$last = mysql_fetch_array($lasti); 
								return $last[0];
					}
					public function showFields($table){
						$ins = Connection::connect(MODE);
						$query = mysql_query("show columns from ".$table);
						mysql_close();
						return $query;
					}
					public function showTables($banco){
						$ins = Connection::connect(MODE);
						$query = mysql_query("show tables") or die(mysql_error());
						mysql_close();
						return $query;
					}
					public function filter($data, $table){ //método que retira qualquer campo que não pertence a table a ser trabalhada
								$i = 0;	
								$query = mysql_query("show columns from ".$table);
								$fields = array();
								while($res = mysql_fetch_array($query)):
									if($res[0] != "id".$table): //aqui seria o id primary da table , o meu fica "idusuario, idcliente" assim consigo validar
											$fields[$res[0]] = $res[0];
									endif;
								endwhile;
								foreach($data as $key => $value):
									
										if($key == "request"):
										else:
													if($key == @$fields[$key]):
																	
																$fields[$key] = $data[$key];
													else:
																	
													endif;
										endif;
								endforeach;
								
								return $fields;
					}
					
					public function insert($data, $table, $foreignKey = 0){
								$i = 0;
								$ins = Connection::connect(MODE);
								//$ins = Connection::connect();
							
								$data = self::filter($data, $table); 
							
								$res = "insert into ".$table." values(null,";
										
										foreach($data as $key => $value):
												if($key == "request"):
															$res .= "";
												else:
															if($i < sizeof($data)-1){
																	if(is_numeric($value)):
																		$res .= $value.",";
																	else:
															 			$res .= "'".$value."',";
																	endif;
															}else{
																	if(is_numeric($value)):
																		$res .= $value;
																	else:
															 			$res .= "'".$value."'";
																	endif;
															}
												endif;
													$i++;
										endforeach;
										$res .= ")";
										
								
						
								$insert = mysql_query($res) or die(mysql_error(). " sua query: ".$res);
								if($insert):
											return self::lastID($table);
								else:
											return "erro";
								endif;
					}
					public function insertN2N($data, $table, $foreignKey = 0){
								$i = 0;
								$ins = Connection::connect(MODE);
								$data = self::filter($data, $table); 
								
										$res = "insert into ".$table." values(";
										foreach($data as $key => $value):
												if($key == "request"):
															$res .= "";
												else:
															if($i < sizeof($data)-1){
																	if(is_numeric($value)):
																		$res .= $value.",";
																	else:
															 			$res .= "'".$value."',";
																	endif;
															}else{
																	if(is_numeric($value)):
																		$res .= $value;
																	else:
															 			$res .= "'".$value."'";
																	endif;
															}
												endif;
													$i++;
										endforeach;
										$res .= ")";

								$insert = mysql_query($res) or die(mysql_error(). " sua query: ".$res);
								if($insert):
											return self::lastID($table);
								else:
											return "erro";
								endif;
					}
					
					public function execute(){
								$ins = Connection::connect(MODE);
						
								if(empty(self::$where)):
									if(empty(self::$group)):
										self::$select = 'select '.self::$fields.' from '.self::$table.' '.self::$join." ".self::$order." ".self::$limit;
									else:
										self::$select = 'select '.self::$fields.' from '.self::$table.' '.self::$join." ".self::$group." ".self::$order." ".self::$limit;
									endif;
								else:
									if(empty(self::$group)):
											self::$select = 'select '.self::$fields.' from '.self::$table.' '.self::$join.' where '.self::$where." ".self::$order." ".self::$limit;
									else:
										self::$select = 'select '.self::$fields.' from '.self::$table.' '.self::$join.' where '.self::$where." ".self::$group." ".self::$order." ".self::$limit;
									endif;
								endif;
								
								self::$limit = "";
								self::$group = "";
								
								//self::$where = "";
								
								return mysql_query(self::$select);// or die(mysql_error()." SUA QUERY: ".self::$select);
								#return self::$select;
						
					}
					public function select($fields = "*"){
								self::$fields = $fields;
								return self::$fields;
					}
					public function table($table){
								self::$table = $table;
					}
					public function join($table, $ligacao, $tipo = "inner"){
								self::$join .= $tipo." join ".$table." on ".$ligacao." ";
								
								return self::$join;
					}
					
					public function clearJoin(){
							self::$join = "";
					}
					public function where($campo, $valor, $condicao = "and", $operador = "="){
									if(self::$where == ""):
												if(is_numeric($valor)){
															self::$where .= $campo." = ".$valor." ";
												}elseif($operador == 'like'){
															self::$where .= $campo." ".$operador." '%".$valor."%' ";
												}else{
															self::$where .= $campo." ".$operador." '".$valor."' ";
												}
									else:
											if(is_numeric($valor)){
												self::$where .= " ".$condicao." ".$campo." = ".$valor." ";
											}elseif($operador == 'like'){
												self::$where .= " ".$condicao." ".$campo." ".$operador." '%".$valor."%' ";
											}else{
															self::$where .= " ".$condicao." ".$campo." ".$operador." '".$valor."' ";
											}
									endif;
									
					}
					
					public function update($table, $data, $id = "", $operador = "="){
								$ins = Connection::connect(MODE);
								$data = (is_array($data) && count($data) > 1) ? self::filter($data, $table) : $data; 
								
								$varUpdate = "update ".$table." set ";
								$updateValues = "";
								foreach($data as $key => $value):
										if(is_numeric($value)){
											if($operador == "+"):
													if($updateValues == ""){
															$updateValues .= " ".$key." = ".$key."+".$value;
													}else{
															$updateValues .= ", ".$key." = ".$key."+".$value;
													}
											else:
													if($updateValues == ""){
															$updateValues .= " ".$key." = ".$value;
													}else{
															$updateValues .= ", ".$key." = ".$value;
													}
											endif;
										}else{
												
												if($updateValues == ""){
															$updateValues .= " ".$key." = "."'".$value."'";
												}
												else{
															$updateValues .= ", ".$key." = "."'".$value."'";
												}
										}
								endforeach;
								self::$update = $varUpdate."".$updateValues;
								if(!empty(self::$where)): 
											self::$update .= " where ".self::$where;
								else:
											self::$update .= " where id".$table." = ".$id;
								endif;
								self::$where = "";
								
								return mysql_query(self::$update) or die(mysql_error(). " sua QUERY: ".self::$update);
	
					}
					
					public function clearUpdate(){
								self::$update = "";
								self::$where = "";
					}
					public function orderBy($item, $ordem = 'asc'){
							self::$order = "order by ".$item." ".$ordem;
					}
					public function limit($begin, $end = ""){
						 self::$limit = (empty($end)) ? "limit ".$begin : "limit ".$begin.",".$end;
					}
					public function groupBy($item){
							self::$group = 'group by '.$item;
					}
					public function clearOrder(){
						self::$order = "";
					}
					public function delete($table, $data = "", $id){
							$ins = Connection::connect(MODE);
							if(empty($data)):
								self::$delete = "delete from ".$table." where id".$table." = ".$id;
							else:
								self::$delete = "delete from ".$table." where ".$data." = ".$id;
							endif;
							
							return mysql_query(self::$delete) or die(mysql_error());
					}
					public function selectAll($table){ //ainda em testes 
						
						/* 
						   * a ideia desse método e encapsular um pouco mais a query , específicamente os joins
						   * logicamente os joins estao sendo um pouco genéricos (inner), futuramente pretendo deixar 
						   * livre de escolha qual inner join usar e em qual situação ou tabelas
						   * ainda ta um pouco estruturado , refatorarei em breve e ficará xuxu blza.
						   * exemplo de utilização
						   
						   * Record::selectAll('cliente') //pessoa supondo que tem como tabela pai 'USUARIO';
						   
						   * Output : select * from cliente inner join usuario on cliente.usuario_id = usuario.idusuario; *nome das colunas somente exemplo*
						   * Obs: se tiver outras FK , o método mapeará elas e fara com que atuem na query. 
						   
						   * Objetivo: menos trampo para produzir SQL queries.
						   
						   @author R'Santiago
						*/
						
						$teste = self::showFields($table);
						$teste2 = self::showTables(DB);
						$foreignKeys = array();
						$listedForeignKeys = "";
						$primaryKeyJoins = "";
						$tables = array();
						$joins = array();

	
						while($array = mysql_fetch_array($teste)): // recupero as FK's da tabela 
								foreach($array as $key => $value):
			
									if($value == 'MUL' && is_numeric($key))
										$foreignKeys[] = $array['Field'];

								endforeach;
						endwhile;
	
	
						while($array = mysql_fetch_array($teste2)): // recupero todas as tabelas para uma comparação
								foreach($array as $key => $value):
									if(is_numeric($key))
										$tables[] = $value;
								endforeach;
						endwhile;

						foreach($tables as $key => $value):
			
									foreach($foreignKeys as $key2 => $value2):
						
												if(preg_match("/(${value})/", $value2)): //busco pelo nome da tabela dentro de cada FK
													$joins[] = $tables[$key]; //armazeno num array as tabelas que atuarão nos joins
													$listedForeignKeys[$value] = $value2; //armazeno num vetor as FW que atuarão nos joins
												endif;
					
									endforeach;
			
						endforeach;
			 		  
					  $i = 0;
					  
					  /*
					  		*  no loop abaixo recupero todas as primary keys das tabelas que resgatei para serem usadas nos joins

					  */
					  while($i < count($joins)){
					  			
					  			$teste = self::showFields($joins[$i]);
					  			while($array = mysql_fetch_array($teste)): //percorro o resultado dos campos da tabela no indice atual
										foreach($array as $key => $value):
			
											if($value == 'PRI' && is_numeric($key)) 
												$primaryKeyJoins[$joins[$i]] = $array['Field']; //armazeno no vetor as PK's como cada indice o nome das tabelas
					
					
										endforeach;
								endwhile;
								$i++;
					  }
					
							
					  self::select('*');
					  self::table($table);
					  if(!empty($primaryKeyJoins)):
							  foreach(@$primaryKeyJoins as $key => $value):
							  		
							  				self::join("${key}", "${key}.${value} = ${table}.${listedForeignKeys[$key]}");

							  endforeach;
						else: endif;
						
					  $teste = self::execute() or die('forma errada de executar esse Select =/');
					  return $teste;
					}
			}
?>
