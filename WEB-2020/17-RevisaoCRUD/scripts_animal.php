<script>
    $(function(){
        function define_alterar_remover(){ 
            $(".alterar").click(function(){
                i = $(this).val();
                $("#id_animal_oculto").val(i);
                $.get("seleciona_animal.php?id="+i,function(r){
                    a = r[0];
                    $("#nome_modal").val(a.nome_animal);
                    $("select[name='especie_modal']").val(a.cod_especie);
                     
                    $("select[name='cliente_modal']").val(a.cod_cliente);

                    $.post("select_raca.php",{especie:a.cod_especie},function(r){
                        texto = "<option label=\"::Selecione a Raça::\"></option>";
                        $.each(r.raca,function(i,v){
                                texto += "<option value=" +v.id_raca+ ">" +v.nome+ "</option>";
                        });
                        $("#cod_raca_modal").html(texto);
                        $("#cod_raca_modal").val(a.cod_raca);
                    });
                });
            });

            $(".remover").click(function(){
                i = $(this).val();
                c = "id_animal";
                t = "animal";
                p = {tabela:t,id:i,coluna:c}
                $.post("remover.php",p,function(r){
                        $("#msg").removeClass("alert alert-danger");
                        $("#msg").removeClass("alert alert-info");
                        if(r=='1'){                
                            $("#msg").addClass("alert alert-info");
                            $("#msg").html("Animal removido com sucesso.");
                            $("button[value='"+ i +"']").closest("tr").remove();
                        }
                });
            });

            $("#especie_modal").change(function(){
            var especie = $("#especie_modal").val();
            $.post("select_raca.php",{"especie":especie},function(racas){
                console.log(racas);
                texto = "<option label=\"::Selecione a Raça::\"></option>";
                $.each(racas.raca,function(i,v){
                    texto += "<option value=" +v.id_raca+ ">" +v.nome+ "</option>";
                });
                $("#cod_raca_modal").html(texto);
            });
        });

        }

        define_alterar_remover();

        $(".salvar").click(function(){ 
           p = {
                nome:$("input[name='nome_modal']").val(),
                cliente:$("select[name='cliente_modal']").val(),
                raca:$("select[name='cod_raca_modal']").val(),
                id_animal:$("#id_animal_oculto").val()
           };  
           console.log(p);      
           
            $.post("atualizar_animal.php",p,function(r){
            $("#msg").removeClass("alert alert-danger");
            $("#msg").removeClass("alert alert-info");
            if(r=='1'){
                $("#msg").addClass("alert alert-info");
                $("#msg").html("Animal alterado com sucesso.");
                $(".close").click();
                atualizar_tabela();                
            }else{
                $("#msg").addClass("alert alert-danger"); 
                $("#msg").html("Falha ao atualizar Animal.");
            }
           });
       }); 

       function atualizar_tabela(){           
            $.get("seleciona_animal.php",function(r){
                t = "";
                $.each(r,function(i,a){              
                    t += "<tr>";
                    t +=    "<td>"+a.nome_animal+"</td>";
                    t +=    "<td>"+a.nome_especie+"</td>";
                    t +=    "<td>"+a.nome_raca+"</td>";
                    t +=    "<td>"+a.nome_cliente+"</td>";
                    t +=    "<td>";
                    t +=        "<button class='btn btn-warning alterar' value='"+a.id_animal+"' data-toggle='modal' data-target='#modal'>Alterar</button>";
                    t +=        " <button class='btn btn-danger remover' value='"+a.id_animal+"'>Remover</button>";
                    t +=    "</td>";
                    t += "</tr>";
                });            
                $("#tbody_animal").html(t);
                define_alterar_remover();
            });
        }
    });


</script>