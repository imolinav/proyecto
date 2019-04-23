<?php
require_once "metodos.php";

if(isset($_POST['comunidad'])) {
    $provincias = cargaProvincias($conexion, $_POST['comunidad']);
    if(!empty($provincias)): ?>
<select class="form-control mt-3" id="select_provincia">
    <option selected disabled class="text-muted"><?= $i_gtiempo_option1 ?></option>
    <option disabled class="text-muted">------------------------------</option>
    <?php foreach($provincias as $provincia) :?>
    <option value="<?= $provincia['id'] ?>"><?= $provincia['provincia'] ?></option>
    <?php endforeach; ?>
</select>
<?php
    endif;
} else if (isset($_POST['provincia'])) {
    $municipios = cargaMunicipios($conexion, $_POST['provincia']);
    if(!empty($municipios)): ?>
<select class="form-control mt-3" id="select_municipio">
    <option selected disabled class="text-muted"><?= $i_gtiempo_option2 ?></option>
    <option disabled class="text-muted">------------------------------</option>
    <?php foreach($municipios as $municipio) :?>
    <option value="<?= $municipio['latitud'] ?>,<?= $municipio['longitud'] ?>"><?= $municipio['municipio'] ?></option>
    <?php endforeach; ?>
</select>
<?php
    endif;
} else if(isset($_POST['municipio'])) {
    $lat_lon = explode(",", $_POST['municipio']);
    $data_parsed = cargaTiempo($lat_lon[0], $lat_lon[1]);
    $data_aux = $data_parsed;
    $data_aux2 = $data_parsed;
    $fecha_ms = time();
    $fechas = [];
    $fechas2 = [];
    for($i=0; $i<5; $i++) {
        array_push($fechas, date("d/m/Y", $fecha_ms));
        array_push($fechas2, date("Y-m-d", $fecha_ms));
        $fecha_ms += 86400;
    }
    $colores = ['#249bdc', '#247bae', '#446982', '#5aacc3', '#63717d', '#44556d'];
    //soleado, medio-nublado, nublado/tormenta, nieve, lluvia, noche
    if(!empty($data_parsed)): ?>
        <ul class="nav nav-tabs nav-fill mb-3" id="myTab" role="tablist">
            <?php for($i=1; $i<=5; $i++): ?>
            <li class="nav-item">
                <?php if($i==1) :?>
                <a class="nav-link active" id="dia<?= $i ?>_tab" data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="true"><?= $fechas[$i-1] ?></a>
                <?php else :?>
                <a class="nav-link" id="dia<?= $i ?>_tab" data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="false"><?= $fechas[$i-1] ?></a>
                <?php endif; ?>
            </li>
            <?php endfor; ?>
        </ul>
        <div class="tab-content" id="myTabContent">
            <?php for($j=1; $j<=5; $j++) {
                $aux = 0;
                $aux2 = 0;
                if($j==1) :?>
                    <div class="tab-pane fade show active" id="dia<?= $j ?>" role="tabpanel" aria-labelledby="dia<?= $j ?>_tab">
                <?php else: ?>
                    <div class="tab-pane fade" id="dia<?= $j ?>" role="tabpanel" aria-labelledby="dia<?= $j ?>_tab">
                <?php endif; ?>
                        <!--  Aqui va el carousel  -->
                        <div id="carouselTiempo<?= $j ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                            <ol class="carousel-indicators">
                            <?php for($k=0; $k<count($data_aux2['list']); $k++) {
                                $dia = explode(" ", $data_aux2['list'][$k]['dt_txt']);
                                if($dia[0]==$fechas2[$j-1]) {
                                    if($aux2==0) :?>
                                    <li data-target="#carouselTiempo<?= $j ?>" data-slide-to="<?= $aux2 ?>" class="active"></li>
                                    <?php else: ?>
                                    <li data-target="#carouselTiempo<?= $j ?>" data-slide-to="<?= $aux2 ?>"></li>
                                    <?php endif;
                                    $aux2++;
                                    array_splice($data_aux2, $k, 1);
                                }
                            } ?>
                            </ol>
                            <div class="carousel-inner">
                            <?php for($k=0; $k<count($data_aux['list']); $k++) {
                                $dia = explode(" ", $data_aux['list'][$k]['dt_txt']);
                                if($dia[0]==$fechas2[$j-1]) {
                                    if($aux==0) :?>
                                    <div class="carousel-item active">
                                    <?php else: ?>
                                    <div class="carousel-item">
                                    <?php endif; ?>

                                        <?php if($data_aux['list'][$k]['weather'][0]['main'] == "Clear") :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[0] ?>"></div>
                                        <div class="icon sunny">
                                            <div class="sun">
                                                <div class="rays"></div>
                                            </div>
                                        </div>
                                        <?php elseif($data_aux['list'][$k]['weather'][0]['id'] == 800) :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[1] ?>"></div>
                                        <div class="icon cloudy">
                                            <div class="cloud"></div>
                                            <div class="cloud"></div>
                                        </div>


                                        <?php elseif($data_aux['list'][$k]['weather'][0]['main'] == "Clouds") :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[2] ?>"></div>
                                        <div class="icon cloudy">
                                            <div class="cloud"></div>
                                            <div class="cloud"></div>
                                        </div>

                                        <?php elseif($data_aux['list'][$k]['weather'][0]['id'] >= 500 && $data_aux['list'][$k]['weather'][0]['id'] <= 504) :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[4] ?>"></div>
                                        <div class="icon sun-shower">
                                            <div class="cloud"></div>
                                            <div class="sun">
                                                <div class="rays"></div>
                                            </div>
                                            <div class="rain"></div>
                                        </div>

                                        <?php elseif($data_aux['list'][$k]['weather'][0]['main'] == "Rain") :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[2] ?>"></div>
                                        <div class="icon rainy">
                                            <div class="cloud"></div>
                                            <div class="rain"></div>
                                        </div>


                                        <?php elseif($data_aux['list'][$k]['weather'][0]['main'] == "Snow") :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[3] ?>"></div>
                                        <div class="icon flurries">
                                            <div class="cloud"></div>
                                            <div class="snow">
                                                <div class="flake"></div>
                                                <div class="flake"></div>
                                            </div>
                                        </div>

                                        <?php elseif($data_aux['list'][$k]['weather'][0]['main'] == "Thunderstorm") :?>
                                        <div class="temp_fondo" style="background-color: <?= $colores[2] ?>"></div>
                                        <div class="icon thunder-storm">
                                            <div class="cloud"></div>
                                            <div class="lightning">
                                                <div class="bolt"></div>
                                                <div class="bolt"></div>
                                            </div>
                                        </div>

                                        <?php endif; ?>

                                        <div class="temp_info">
                                            <h1><?= $data_aux['list'][$k]['main']['temp'] ?>ºC</h1>
                                            <div style="text-transform: capitalize"><?= $data_aux['list'][$k]['weather'][0]['description'] ?></div>
                                            Min: <?= $data_aux['list'][$k]['main']['temp_min'] ?>ºC - Max: <?= $data_aux['list'][$k]['main']['temp_max'] ?>ºC<br>
                                            <?= $i_gtiempo_texto1 ?><?= $data_aux['list'][$k]['main']['humidity'] ?>% <br>
                                            <?= $i_gtiempo_texto2 ?><?= $data_aux['list'][$k]['wind']['speed'] ?>m/s <br>
                                            <?= $dia[1] ?><br>
                                        </div>
                                    </div>
                                    <?php
                                    $aux++;
                                    array_splice($data_aux, $k, 1);
                                }
                            } ?>
                            </div>
                        </div>
                    </div>
            <?php } ?>
        </div>
    <?php endif;
}
?>