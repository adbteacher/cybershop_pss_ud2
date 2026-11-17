<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$pageTitle = 'Aviso legal';
require __DIR__ . '/includes/header.php';
?>
<h1>Aviso legal, privacidad y proyecto docente</h1>

<div class="legal-box">
    <h2 id="laboratorio">Sobre este proyecto</h2>
    <p>
        <strong>CyberShop</strong> es una tienda online de demostracion desarrollada como
        <strong>laboratorio docente del CIPFP Mislata</strong>, dentro del Ciclo Formativo de
        Grado Superior en Ciberseguridad en Entornos de las Tecnologias de la Informacion,
        para el modulo <strong>5023 — Puesta en produccion segura</strong>.
    </p>
    <p>
        El catalogo de productos, los usuarios, los pedidos y las opiniones que se muestran en
        esta web son <strong>datos ficticios generados con fines educativos</strong>. No es una
        tienda real, no procesa pagos reales y no debe utilizarse con datos personales,
        contraseñas ni tarjetas verdaderas. El despliegue esta pensado para ejecutarse en un
        entorno de laboratorio aislado (red docente sin salida a Internet), como parte de una
        practica autorizada por el profesorado del ciclo.
    </p>

    <h2 id="privacidad">Politica de privacidad</h2>
    <p>
        Esta instancia de demostracion no recopila datos con fines comerciales ni los comparte
        con terceros. Los formularios de registro, contacto y perfil almacenan la informacion
        introducida unicamente en la base de datos local del laboratorio, con el proposito de
        que el alumnado pueda auditar su tratamiento como parte del ejercicio de la asignatura.
    </p>

    <h2 id="cookies">Politica de cookies</h2>
    <p>
        El sitio utiliza una cookie de sesion (<code>PHPSESSID</code>) necesaria para el
        funcionamiento del carrito y del inicio de sesion, y una cookie opcional
        <code>remember</code> asociada a la opcion "Recordarme" del formulario de acceso.
    </p>

    <h2 id="envios">Envios y devoluciones</h2>
    <p>
        Al tratarse de un entorno de practicas, ningun pedido genera un envio real. Los plazos,
        precios de envio y politica de devoluciones mostrados en la web son contenido de
        ejemplo para dar realismo a la experiencia de usuario.
    </p>

    <h2>Uso responsable</h2>
    <p>
        Esta aplicacion contiene vulnerabilidades introducidas de forma intencionada con
        finalidad exclusivamente formativa. Su uso queda restringido al alumnado y profesorado
        del CIPFP Mislata en el marco de una practica autorizada, dentro de una red aislada de
        laboratorio. Queda desaconsejado su despliegue en redes accesibles desde Internet o con
        cualquier dato real de caracter personal o financiero.
    </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
