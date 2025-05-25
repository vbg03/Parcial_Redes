const { Router } = require('express');
const router = Router();
const seguidoresModel = require('../Models/seguidoresModel');

// Crear una relación de seguimiento
router.post('/seguidores', async (req, res) => {
    const { usuarioP, usuarioS } = req.body;

    if (usuarioP === usuarioS) {
        res.status(400).send("Un usuario no puede seguirse a sí mismo");
        return;
    }

    await seguidoresModel.crearRelacion(usuarioP, usuarioS);
    res.send("Relación de seguimiento creada");
});

// Obtener a quién sigue un usuario
router.get('/seguidores/:usuarioS', async (req, res) => {
    const usuarioS = req.params.usuarioS;
    const resultado = await seguidoresModel.obtenerSeguidos(usuarioS);
    res.json(resultado);
});

module.exports = router;
