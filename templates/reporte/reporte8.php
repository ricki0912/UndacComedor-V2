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
          <h3 class="card-header text-center bg-primary" style="color: #fff; font-weight: bold; font-family: 'arial narrow';">WEB-MOVIL(APP)<span class="fas fa-chart-bar pull-right"></span></h3>
          <div class="card-block p-3">


           



            <div class="card-body table-responsive p-0" id="tabla_resultado">
              <canvas id="myChart" width="400" height="200"></canvas>

              <div class="text-center" id="mensajeError">

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
        url: 'http://comedor.undac.edu.pe:8094//reporte/get_all_reservation_last_23_day',
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
        
        var data_json_web=new Array();
        var data_json_movil=new Array();
        var labels_json=new Array();
          for(var i=resp.length-1;i>=0;i--){
              data_json_web.push(resp[i].web);
              data_json_movil.push(resp[i].movil);
              labels_json.push(resp[i].datee);
          }

        

          //var solvet = JSON.parse(resp);

          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'line',
              data: {
                  labels: labels_json /*['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']*/,
                  datasets: [

                  {
                      label: '# de reservaciones desde dia-Web',
                      data: data_json_web/*[15, 20, 5, 9, 4]*/,
                      backgroundColor: [
                          'rgba(54, 162, 235, 0.2)'
                      ],
                      borderColor: [
                          'rgba(54, 162, 235, 1)'
                      ],
                      borderWidth: 1
                  },
                  {
                      label: '# de reservaciones desde dia-Movil(App) ',
                      data:   data_json_movil/*[12, 19, 3, 5, 2]*/,
                      backgroundColor: [
                          'rgba(255, 99, 132, 0.2)'
                      ],
                      borderColor: [
                          'rgba(255, 99, 132, 1)'
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