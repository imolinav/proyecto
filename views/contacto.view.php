<div class="container mt-5 mb-5">
    <form method="post" action="link.php">
        <div class="row">
            <div class="col-4 offset-2">
                <p>Nombre:</p>
            </div>
            <div class="col-4">
                <p>E-mail:</p>
            </div>
        </div>
        <div class="row">
            <div class="col-4 offset-2">
                <input type="text" class="form-control" name="nombre_contacto">
            </div>
            <div class="col-4">
                <input type="text" class="form-control" name="email_contacto">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4 offset-2">
                <p>Razón de contacto:</p>
            </div>
            <div class="col-4">
                <p>Otros:</p>
            </div>
        </div>
        <div class="row">
            <div class="col-4 offset-2">
                <select class="form-control">
                    <option value="opcion1">Duda sobre el precio</option>
                    <option value="opcion2">Problemas técnicos</option>
                    <option value="opcion3">Duda sobre el precio</option>
                    <option value="opcion4">Duda sobre el precio</option>
                    <option value="opcion5">Duda sobre el precio</option>
                    <option value="opcion6">Otros...</option>
                </select>
            </div>
            <div class="col-4">
                <input type="text" class="form-control" name="opcion_contacto" disabled>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4 offset-2">
                <p>Mensaje:</p>
            </div>
        </div>
        <div class="row mt-0">
            <div class="col-8 offset-2">
                <textarea class="form-control"></textarea>
            </div>
        </div>
        <small class="form-text text-muted offset-2">Te contestaremos lo más rápido posible</small>
        <input type="submit" name="submit" class="btn btn-primary mt-3 offset-2" value="Enviar e-mail">
    </form>
</div>

<script>
    document.getElementById('logout').onclick = logout;
    document.getElementsByTagName('select')[0].onchange = habilitarInput;

    function habilitarInput(event) {
        if(event.target.value === "opcion6") {
            document.getElementsByTagName('input')[4].disabled = false;
        } else {
            document.getElementsByTagName('input')[4].disabled = true;
        }
    }

    function logout(event){
        event.target.parentNode.submit();
    }
</script>
