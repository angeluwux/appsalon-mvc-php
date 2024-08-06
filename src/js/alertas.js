document.addEventListener('DOMContentLoaded', () => {
    borrarAlertas();
})

let alerta = document.querySelectorAll('.alerta');

function borrarAlertas() {
    setTimeout(() => {
        alerta.forEach(alerta => {
            alerta.remove()
        });
    }, 3000);
}
