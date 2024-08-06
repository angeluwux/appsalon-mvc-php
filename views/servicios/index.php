<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php include_once __DIR__ . '/../templates/registros.php'; ?>
<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<ul class="servicios">
    <?php foreach ($servicios as $servicio) : ?>
        <li>
            <p>Nombre: <span><?= $servicio->nombre ?></span></p>
            <p>Precio: <span>$ <?= $servicio->precio ?></span></p>

            <div class="act">
                <a class="actBoton actualizar" href="/servicios/actualizar?id=<?=$servicio->id?>">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                <input type="hidden" name="id" value="<?=$servicio->id ?>">
                <input type="submit" value="Borrar" class="actBoton eliminar">
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>


<?php $script = "<script src='/build/js/alertas.js'></script>"; ?>