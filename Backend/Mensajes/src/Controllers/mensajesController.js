const { Router } = require('express');
const router = Router();
const mensajesModel = require('../Models/mensajesModel');
const axios = require('axios');

// Obtener mensajes por usuario
router.get('/mensajes/usuario/:id_usuario', async (req, res) => {
    const id_usuario = req.params.id_usuario;
    const resultado = await mensajesModel.obtenerMensajesPorUsuario(id_usuario);
    res.json(resultado);
});

// Crear mensaje con validación por axios
router.post('/mensajes', async (req, res) => {
    const { id_usuario, contenido } = req.body;

    try {
        const respuesta = await axios.get(`http://192.168.100.2:3001/usuarios/${id_usuario}`);
        if (!respuesta.data || respuesta.status !== 200) {
            return res.status(400).send("Usuario no válido");
        }

        await mensajesModel.crearMensaje(id_usuario, contenido);
        res.send("Mensaje creado correctamente");

    } catch (error) {
        res.status(400).send("Error validando el usuario");
    }
});

router.delete('/mensajes/:id', async (req, res) => {
    const id = req.params.id;
    await mensajesModel.eliminarMensaje(id);
    res.send("Mensaje eliminado");
});


module.exports = router;
