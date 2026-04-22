<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>HotelOS PMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    height:100vh;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

    background:
        radial-gradient(circle at 15% 20%, rgba(0,180,255,.25), transparent 40%),
        radial-gradient(circle at 85% 80%, rgba(0,80,200,.25), transparent 40%),
        linear-gradient(rgba(8,15,40,.80), rgba(8,15,40,.96)),
        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070');

    background-size:cover;
    background-position:center;

    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== CONTENEDOR ===== */

.login-container{
    width:460px;
    backdrop-filter: blur(18px);
    background: rgba(255,255,255,0.07);
    border-radius:22px;
    padding:48px;
    box-shadow:
        0 50px 120px rgba(0,0,0,.75),
        inset 0 0 40px rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.12);
    animation:fadeIn .7s ease;
    position:relative;
}

/* brillo superior */

.login-container::before{
    content:'';
    position:absolute;
    top:0;
    left:40px;
    right:40px;
    height:2px;
    background:linear-gradient(90deg,transparent,#00b4ff,transparent);
    opacity:.8;
}

/* animación */

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(30px) scale(.98);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

/* ===== LOGO ===== */

.logo{
    text-align:center;
    color:white;
    font-size:44px;
    font-weight:800;
    letter-spacing:3px;
}

.logo span{
    color:#00b4ff;
}

.subtitle{
    text-align:center;
    color:rgba(255,255,255,.65);
    font-size:12px;
    letter-spacing:2px;
    margin-bottom:34px;
}

/* ===== INPUTS ===== */

.form-label{
    color:rgba(255,255,255,.85);
    font-size:13px;
    margin-bottom:6px;
}

.form-control{
    height:52px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.15);
    background:rgba(255,255,255,.12);
    color:white;
    font-size:15px;
    transition:.25s;
}

.form-control::placeholder{
    color:rgba(255,255,255,.4);
}

.form-control:focus{
    background:rgba(255,255,255,.22);
    border-color:#00b4ff;
    box-shadow:0 0 0 3px rgba(0,180,255,.25);
    color:white;
}

/* ===== BOTON ===== */

.btn-login{
    height:54px;
    border-radius:12px;
    border:none;
    font-weight:700;
    letter-spacing:.7px;
    font-size:15px;
    background:linear-gradient(90deg,#00b4ff,#0066ff);
    transition:.35s;
    position:relative;
    overflow:hidden;
}

.btn-login:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 35px rgba(0,102,255,.45);
}

/* efecto brillo botón */

.btn-login::after{
    content:'';
    position:absolute;
    top:0;
    left:-60%;
    width:50%;
    height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);
    transform:skewX(-25deg);
}

.btn-login:hover::after{
    animation:shine .8s;
}

@keyframes shine{
    100%{
        left:130%;
    }
}

/* ===== FOOTER ===== */

.footer{
    text-align:center;
    margin-top:26px;
    color:rgba(255,255,255,.5);
    font-size:11px;
    letter-spacing:.5px;
}

.version{
    position:fixed;
    bottom:18px;
    right:22px;
    color:rgba(255,255,255,.35);
    font-size:11px;
    letter-spacing:1px;
}

</style>
</head>

<body>

<div class="login-container">

    <div class="logo">Hotel<span>OS</span></div>
    <div class="subtitle">PROFESSIONAL PROPERTY MANAGEMENT SYSTEM</div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('login') ?>">

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" placeholder="Ingresa tu usuario" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button class="btn btn-login w-100 mt-3 text-white">
            🔐 ACCEDER AL SISTEMA
        </button>

    </form>

    <div class="footer">
        HotelOS PMS · Plataforma profesional hotelera
    </div>

</div>

<div class="version">
    v1.0 Enterprise
</div>

</body>
</html>