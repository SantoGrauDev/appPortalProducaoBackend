<?php 
//incluindo conexão
include_once 'conexao.php';           
                  
                    $hoje = date('d/m/Y');

                    $where = "WHERE DATA_TERMINO =  '" .$hoje. "'";

                    $sql = "UPDATE TB_CADPROMO_PROD SET STATUS_CAMPANHA = 'INATIVO' {$where} ";

                    if ($conn->query($sql) === TRUE) {
                      echo "Record updated successfully";
                    } else {
                      echo "Error updating record: ";
                    }       
                  
                ?>

