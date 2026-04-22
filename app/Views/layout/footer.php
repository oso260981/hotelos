</div>

<script>
function reloj(){
let f = new Date()
document.getElementById('clock').innerHTML = f.toLocaleTimeString()
document.getElementById('date').innerHTML = f.toLocaleDateString()
}
setInterval(reloj,1000)
reloj()


function showLoader(text = "Procesando...") {
    const loader = document.getElementById("globalLoader");
    loader.style.display = "flex";
    loader.querySelector(".loader-text").innerText = text;
}

function hideLoader() {
    document.getElementById("globalLoader").style.display = "none";
}




</script>

</body>
</html>