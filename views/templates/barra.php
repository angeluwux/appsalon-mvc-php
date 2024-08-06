<div class="barra contenedor">
    <p>Bienvenido: <?php echo ($nombre); ?></p>
    <a href="/logout" class="boton">Cerrar Sesión</a>
</div>

<?php if ($_SESSION['admin'] === '1') : ?>
    <div class="nav">
        <a href="/admin">Ver Citas</a>
        <a href="/servicios">Ver Servicios</a>
        <a href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php endif; ?>