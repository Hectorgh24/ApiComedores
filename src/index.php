<?php
require_once __DIR__ . '/../middleware/Cors.php';
Cors::permitirOrigen();

require_once __DIR__ . '/DesayunosComidasCarta/routes/DesayunoComidaRoutes.php';
require_once __DIR__ . '../../src/ProductosCarta/routes/ProductoCartaRoutes.php';
require_once __DIR__ . '../src/Comentarios/routes/ComentariosRoutes.php';

?>