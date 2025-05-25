const { Router } = require('express');
const router = Router();
const mensajesModel = require('../Models/mensajesModel');

// Crear mensaje
router.post('/mensajes', async (req, res) => {
    const { id_usuario, contenido } = req.body;
    const resultado = await mensajesModel.crearMensaje(id_usuario, contenido);
    res.send("Mensaje creado correctamente");
});

// Obtener mensajes por usuario
router.get('/mensajes/usuario/:id_usuario', async (req, res) => {
    const id_usuario = req.params.id_usuario;
    const resultado = await mensajesModel.obtenerMensajesPorUsuario(id_usuario);
    res.json(resultado);
});

module.exports = router;
