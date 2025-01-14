<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?> 


<form class="formulario" method="POST" action="/olvide-password">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Tu email"
            name="email">
    </div>
    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
</div>

<?php 
$script = "
     <script src='/build/js/alertas.js'></script>
"; 
?>