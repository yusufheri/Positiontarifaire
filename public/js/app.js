
$(document).ready(function(){  

    $('#selphoto').filestyle({
        btnClass: "btn-primary",
        placeholder: "Aucun fichier sélectionné",
        buttonName : 'btn btn-primary',
        buttonText : ' Fichier Excel (.XLS, .XLXS)',
        iconName : 'fa fa-download'
    });

    /*$("input[type=file]").change(function(){
        alert($(this).val())
    })*/

    
    /*
    $("form[name=importForm]").on('submit', function(e){
        e.preventDefault();

        var form = $("form[name=importForm]");
        console.log(form)
        $.ajax({
            url: "/dashboard/product/import",
            type: 'POST',
            method: 'POST',
            data: form.serialize(),
            beforeSend : function(){
                form.notify("Enregistrement en cours ...", {className: "info", position:"t c" });
                $("#btnSubmit").addClass('disabled')
                $("#illustration").addClass('disabled')
            },
            success: function(data) {
                //console.log(data.indexOf("succès"))
                if(data.indexOf("succès") >= 0) {
                    form.notify(data, {className: "success", position:"t c" }); 
                    $('form').trigger("reset");
                    datatable();  
                } else {
                    //console.log(data) //"Erreur de validation!!"
                    form.notify(data, {className: "error", position:"t c" });
                }              
            }, error : function(error) {
                form.notify(error, {className: "error", position:"t c" });
            }, complete: function() {
                $("#btnSubmit").removeClass('disabled')
                $("#illustration").removeClass('disabled')
            }
        })

    })
    */
    
    function datatable(url = "/dashboard/product/api"){
        $('#datatable').notify("Chargement des données en cours ...", {className: "info", position:"t c" });
        var baseurl = url;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET",baseurl,true);
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState==4 && xmlhttp.status ==200){
                var data = JSON.parse(xmlhttp.responseText);
                $("#count").html(data.length)   
                var table = $("#example").DataTable({
                    "language": {
                        "decimal":        "",
                        "emptyTable":     "<h1>Aucune donnée disponible</h1>",
                        "info":           "Afficher _START_ à _END_ sur _TOTAL_ ligne(s)",
                        "infoEmpty":      "Afficher 0 à 0 sur 0 ligne(s)",
                        "infoFiltered":   "(<b>Filtré de _MAX_ ligne(s) au total</b>)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "Afficher _MENU_ lignes",
                        "loadingRecords": "Chargement...",
                        "processing":     "Traitement en cours...",
                        "search":         "Recherche:",
                        "zeroRecords":    "<h2>Aucun enregistrements correspondants trouvés</h2>",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        }
                    },
                    destroy: true,
                    responsive: true,
                    autoFill: true,
                    data:data,
                    "columns":[
                        {"title": "", "data": null, defaultContent: '' },
                        {"title": "Id", "data":"id" , "visible":false},
                        /*{"title": "Créé", "data": null, render: function(data, type, row) {
                            return "<center>" + moment(row.createdAt).format('DD-MM-YYYY').toString() + "</center>"
                        }},*/
                        {"title": "Position Tarifaire", "data": "position"},
                        {"title": "Libellé", "data": "libelle"},
                        {"title": "Date Début", "data": "debut"},
                        {"title": "DDI", "data": "ddi"},
                        {"title": "DCL", "data": "dcl"},
                        {"title": "DCI", "data": "dci"},                        
                        {"title": "TVA", "data": "tva"},
                        {"title": "Unité", "data": "unite"},
                        {"title": "", "data": null}
                    ],
                    columnDefs: [
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        },
                        { 
                            width: '3%', 
                            targets: 0  //la primer columna tendra una anchura del  20% de la tabla
                        },                       
                        {
                            targets: -1, //-1 est la dernière colonne et 0 la première colonne
                            data: null,
                            defaultContent: '<center> <div class="btn-group"><button type="button" class="btn btn-info btn-xs dt-edit" style="margin-right:6px;"><i class="fas fa-edit"></i></button>  <button type="button" class="btn btn-danger btn-xs dt-delete" ><i class="fas fa-trash"></i></button></div></center>'
                        },
                        { orderable: false, searchable: false, targets: -1 } 
                    ],
                });

                $("#example tfoot th").each(function() {
                    var title = $(this).text();
                    $(this).html('<input type="text" class="form-control" placeholder="' + title +'"/>');
                });

                table.columns().every(function(){
                    var that = this;
                    $('input', this.footer()).on('keyup change', function() {
                        if(that.search !== this.value){
                            that.search(this.value).draw();
                        }
                    })
                });  
                
                $("#example").on('click', '.dt-edit', function(){
                    var data = table.row($(this).parents('tr')).data();
                    try {
                        console.log("Début du proccess..")
                        $.ajax({
                            url:"/dashboard/product/"+ data.id.toString() +"/edit_form",
                            method:"POST",
                            type:"POST",
                            beforeSend:function(){
                                $(".modalCompte").html("<center><h2>Chargement en cours...</h2></center>")
                                $("#FormCompte").modal('show')
                            },
                            success: function(data){                           
                                $(".modalCompte").html(data)                            
                            }, error: function(error){
                                $(".modalCompte").html(error)
                            }, complete:function(){
                                //$("#header").html("<header>Modification de la position tarifaire</header>")
                                $("#btnSave").html('<i class="fa fa-check"></i> Modifier')
                            }
                
                        })
                    } catch (error) {
                        //console.error("error")
                    }
                    finally{
                        //console.log("finally")
                    }                   
                })

                $("#example").on('click', '.dt-delete', function(e){
                    e.preventDefault();
                    var data = table.row($(this).parents('tr')).data();
                    try {
                        if (confirm('Voulez-vous supprimer cette position tarifaire ?')) 
                        {
                            $.ajax({
                                method:"POST",
                                type:"POST",
                                url:"/dashboard/product/"+data.id.toString() + "/delete",
                                beforeSend: function(){
                                    $('#example').notify("Suppression en cours...", {className: "info", position:"t c" });
                                    //button.fadeOut(3000).fadeIn(3000);
                                }, success: function(data) {
                                    console.log(data)
                                    $('#example').notify("Suppression réussie avec succès...", {className: "success", position:"t c" });
                                    //location.reload()
                                    datatable()
                                }, error: function(error){
                                    console.error(error);
                                }
        
                            })
                        } else {
                            $('#example').notify("Suppression annulée avec succès...", {className: "info", position:"t c" });
                        }
                        
                    } catch (error) {
                        //console.error("error")
                    }
                    finally{
                        //console.log("finally")
                    }
                })



            }
        };
        xmlhttp.send();
    }

    datatable();

    $("#FormCompte").on("click","#btnCancel", function() {
        $("#FormCompte").modal('hide')
    })

    $("#addProduct").on('click', function(){
        $.ajax({
            url:"/dashboard/product/create_form",
            method:"POST",
            type:"POST",
            beforeSend:function(){
                $(".modalCompte").html("<center><h2>Chargement en cours...</h2></center>")
                $("#FormCompte").modal('show')
            },
            success: function(data){
                $(".modalCompte").html(data)
            }, error: function(error){
                $(".modalCompte").html(error)
            }, complete:function(){
                
            }

        })
    })

    $("#FormCompte").on('submit','form', function(e){
        e.preventDefault();
        var type = $(this).attr('id');
        var id = $("input[name='id']").val();
        var url = (type == "create")?"/dashboard/product/new":"/dashboard/product/"+ id.toString() +"/edit"
        var form_name = (type == 'create')?"product":"edit_product";
        $.ajax({
            url: url,
            type: 'POST',
            method: 'POST',
            data: $("form[name='"+ form_name +"']").serialize(),
            beforeSend : function(){
                $('#FormCompte').notify("Enregistrement en cours ...", {className: "info", position:"t c" });
                $("#btnSave").addClass('disabled')
                $("#btnCancel").addClass('disabled')
            },
            success: function(data) {
                //console.log(data.indexOf("succès"))
                if(data.indexOf("succès") >= 0) {
                    $('#infoBox').notify(data, {className: "success", position:"t c" }); 
                    $("form[name='"+ form_name +"']").trigger("reset"); 
                    $("#message").html("")
                    datatable();  
                } else {
                    $("#message").html(data)
                    $('#infoBox').notify("Erreur de validation!!", {className: "error", position:"t r" });
                }
                            
            }, error : function(error) {
                $('#infoBox').notify(error, {className: "error", position:"t c" });
            }, complete: function() {
                $("#btnSave").removeClass('disabled')
                $("#btnCancel").removeClass('disabled')
                if (type == "edit") { $("#FormCompte").modal('hide') }
            }
        })
    })

    $("#illustration").on('click', function(e) {
        $("#fileImport").modal('hide')
        $("#previewFileImported").modal('show')
    })

    $("#importFile").on('click', function(e) {
        $("#fileImport").modal('show')
    })

    $('#previewFileImported').on('hide.bs.modal', function (e) {
        $("#fileImport").modal('show')
      })
})