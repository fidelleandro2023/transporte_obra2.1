<section class="content content--full">
<div class="content__inner">
<h2 style="color: #333333d4;font-weight: 800;text-align: center;">TORO : <?php echo $_GET["id"];?> | TOTAL : <?php echo number_format($dinero["cantidad"]*$dinero["precio"],2,".",",");?> DISPONIBLE : <span style="color:#ec3305"> <?php echo number_format(($dinero["cantidad"]*$dinero["precio"]-$suma["valor"]),2,".",",");?></span></h2>
<hr> 
<div class="card">                                 
<div class="card-block">                      

<div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">             
              <div class="panel-wrapper">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">

<?php echo $tabla;?>

                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div></div></div>                          


                                  
                      </div>


            </section>
        </main>
 
    </body>

</html>