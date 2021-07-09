<script>
    $(function(){
        function define_alterar_remover(){ 
            $(".alterar").click(function(){
                i = $(this).val();
                $("#id_cliente_oculto").val(i);
                $.get("seleciona_cliente.php?id="+i,function(r){
                    c = r[0];
                    $("#nome_modal").val(c.nome);
                    $("#cpf_modal").val(c.cpf);
                    $("#tel_modal").val(c.telefone);
                });
            });

            $(".remover").click(function(){
                i = $(this).val();
                c = "id_cliente";
                t = "cliente";
                p = {tabela:t,id:i,coluna:c}
                $.post("remover.php",p,function(r){
                        $("#msg").removeClass("alert alert-danger");
                        $("#msg").removeClass("alert alert-info");
                        if(r=='1'){    
                            $("#msg").addClass("alert alert-info");            
                            $("#msg").html("Cliente removido com sucesso.");
                            $("button[value='"+ i +"']").closest("tr").remove();
                        }
                        else{
                            $("#msg").addClass("alert alert-danger");            
                            $("#msg").html("Não é possível remover, pois há um animal cadastrado com esse dono");
                        }
                });
       });
        }

        define_alterar_remover();

        $(".salvar").click(function(){ 
           p = {
                nome:$("input[name='nome_modal']").val(),
                id_cliente: $("#id_cliente_oculto").val(),
                cpf:$("#cpf_modal").val(),
                telefone:$("#tel_modal").val()
           };      
           
           $.post("atualizar_cliente.php",p,function(r){
            $("#msg").removeClass("alert alert-danger");
            $("#msg").removeClass("alert alert-info");
            if(r=='1'){
                $("#msg").addClass("alert alert-info");
                $("#msg").html("Cliente alterado com sucesso.");
                $(".close").click();
                atualizar_tabela();                
            }else{
                $("#msg").addClass("alert alert-danger"); 
                $("#msg").html("Falha ao atualizar Cliente.");
            }
           });
       }); 

       function atualizar_tabela(){           
            $.get("seleciona_cliente.php",function(r){
                t = "";
                $.each(r,function(i,e){              
                    t += "<tr>";
                    t +=    "<td>"+e.nome+"</td>";
                    t +=    "<td>"+e.cpf+"</td>";
                    t +=    "<td>"+e.telefone+"</td>";
                    t +=    "<td>";
                    t +=        "<button class='btn btn-warning alterar' value='"+e.id_cliente+"' data-toggle='modal' data-target='#modal'>Alterar</button>";
                    t +=        " <button class='btn btn-danger remover' value='"+e.id_cliente+"'>Remover</button>";
                    t +=    "</td>";
                    t += "</tr>";
                });            
                $("#tbody_cliente").html(t);
                define_alterar_remover();
            });
        }
    });


</script>