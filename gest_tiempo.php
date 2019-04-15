<?php
require_once "metodos.php";

if(isset($_POST['comunidad'])) {
    $provincias = cargaProvincias($conexion, $_POST['comunidad']);
    if(!empty($provincias)): ?>
<select class="form-control mt-3" id="select_provincia">
    <option selected disabled class="text-muted">Elige la provincia</option>
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
    <option selected disabled class="text-muted">Elige el municipio</option>
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
    //$data_parsed['list'][0]['main']['temp'];
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
                                        <img src="imgs/lluvia2.jpg" style="width: 100%;">
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