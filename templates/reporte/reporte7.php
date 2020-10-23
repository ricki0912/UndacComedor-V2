<?php
if (!isset($_SESSION["logged_is_user"])) {
    header("Location: http://comedor.undac.edu.pe:8094/");
    exit();
} elseif ($_SESSION["role"] !== "AD" and $_SESSION["role"] !== "SP" and $_SESSION["role"] !== "RE"  and $_SESSION["role"] !== "BU") {
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location:" . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: http://comedor.undac.edu.pe:8094/");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
  <?php include "../templates/layouts/head1.php";?>
<body class="app sidebar-mini rtl sidenav-toggled" style="min-width: 280px;">
  <?php include "../templates/layouts/header.php";?>
  <?php include "../templates/layouts/menu.php";?>

  <main class="app-content" style="background: white;">
    <div class="container">
      <div class="col-md-12">
        <div class="card">
          <h3 class="card-header text-center bg-primary" style="color: #fff; font-weight: bold; font-family: 'arial narrow';">NÚMERO DE RESERVACIONES POR TIPO DE MENÚ<span class="fas fa-chart-bar pull-right"></span></h3>
          <div class="card-block p-3">

            <div class="card-body table-responsive p-0" id="tabla_resultado">
              <canvas id="myChart" width="400" height="200"></canvas>

              <div class="text-center" id="mensajeErrorGrafica">

              </div>

              <div id="mostrar_loadingH" style="display: none;">
                <?php include "../templates/layouts/loading.html";?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include "../templates/layouts/scriptFinal.php";?>
  

</body>
</html>
  <script src=" https://cdn.jsdelivr.net/npm/chart.js@2.8.0-rc.1/dist/Chart.min.js"></script>

 
<script>

</script>


<script>

  $(document).ready(function(){
           //listarReservasUsuariosF();

    $(".cerrar").click(function(){
      $.ajax({
        url: 'http://comedor.undac.edu.pe:8094/close/login/user/',
        method:'POST',
        success: function(resp) {
          setTimeout(function(){
            location.reload();
          }, 700);

        }
      });
    });


 

    /*call function*/
    showGraphic();




  })


  function showGraphic(){
      $.ajax({
        url: 'http://comedor.undac.edu.pe:8094//reporte/get_all_by_type',
        type: 'POST',
        beforeSend: function(){
          //document.getElementById('mostrar_loadingH').style.display = 'block';
          //document.getElementById('tbodyReporte').style.display = 'none';
        },
        success: function(resp){
          //document.getElementById('mostrar_loadingH').style.display = 'none';
          //document.getElementById('tbodyReporte').style.display = '';
          //var cont=0;
          
        var resp=JSON.parse(resp);
        //console.log(resp);
        //var data_json=new Array();
        var data_json=new Array();
        var labels_json=new Array();
          for(var i=0;i<resp.length;i++){
              data_json.push(resp[i].total);
              if(resp[i].type===1){
                  labels_json.push("Desayuno");
              }else if(resp[i].type===2){
                  labels_json.push("Almuerzo");
              }else if(resp[i].type===3){
                  labels_json.push("Cena");
              } else{
                  labels_json.push("Cancelados");
              }
              
          }

        

          //var solvet = JSON.parse(resp);

          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'doughnut',
              data: {
                  labels: labels_json/*['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']*/,
                  datasets: [

                  {
                      label: '# de Reservaciones por hora',
                      data: data_json/*[15, 20, 5, 9, 4]*/,
                      backgroundColor: [
                         
                          'rgba(255, 87, 51, 0.8)',
                          'rgba(79, 36, 139, 0.8)',
                          'rgba(139, 80, 36 , 0.8)',
                           'rgba(227, 11, 11, 0.8)',
                      ],
                      borderColor: [
                        
                        'rgba(255, 87, 51, 1)',
                        'rgba(79, 36, 139, 1)',
                        'rgba(139, 80, 36 , 1)',
                        'rgba(227, 11, 11, 1)', 

                       ],
                      borderWidth: 1
                  }
                  
                  ]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero: true
                          }
                      }]
                  }
              }
          });   //
       


       
        }
      })
      .done(function() {
        //cleanFormulario();
        $('#botonReporte').html('<i class="fas fa-search"></i>').attr('disabled', false);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {
          alertify.error('No Conectado: Verifique su conexión a Internet.');
          $('#botonhorario').html('Guardar').attr('disabled', false);
        }else if (jqXHR.status == 404) {
          alertify.error('Error [404]: Página no encontrada.');
        }else if (jqXHR.status == 500) {
          alertify.error('Error [500]: Error Servidor Interno.');
        }else if (textStatus === 'timeout') {
          alertify.error('Error de tiempo de espera... :(');
        }
      })
      .always(function() {
        //console.log("complete");
      });

  }





</script>