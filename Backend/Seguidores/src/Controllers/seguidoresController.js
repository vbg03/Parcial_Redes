const { Router } = require('express');
const router = Router();
const seguidoresModel = require('../Models/seguidoresModel');
const axios = require('axios');

// Obtener a quién sigue un usuario
router.get('/seguidores/:usuarioS', async (req, res) => {
    const usuarioS = req.params.usuarioS;
    const resultado = await seguidoresModel.obtenerSeguidos(usuarioS);
    res.json(resultado);
});

router.post('/seguidores', async (req, res) => {
    const { usuarioP, usuarioS } = req.body;

    if (usuarioP === usuarioS) {
        return res.status(400).send("Un usuario no puede seguirse a sí mismo");
    }

    try {
        const userP = await axios.get(`http://192.168.100.2:3001/usuarios/${usuarioP}`);
        const userS = await axios.get(`http://192.168.100.2:3001/usuarios/${usuarioS}`);

        if (!userP.data || !userS.data) {
            return res.status(404).send("Uno o ambos usuarios no existen");
        }

        await seguidoresModel.crearRelacion(usuarioP, usuarioS);
        res.send("Relación de seguimiento creada");

    } catch (error) {
        res.status(400).send("Error al validar usuarios");
    }
});

router.delete('/seguidores/eliminar', async (req, res) => {
    const { usuarioS, usuarioP } = req.body;
    await seguidoresModel.eliminarSeguimiento(usuarioS, usuarioP);
    res.send("Seguimiento eliminado");
});

module.exports = router;
