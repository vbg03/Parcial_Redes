const { Router } = require('express');
const router = Router();
const usuariosModel = require('../Models/usuariosModel');

// GET todos los usuarios
router.get('/usuarios', async (req, res) => {
    const resultado = await usuariosModel.traerUsuarios();
    res.json(resultado);
});

// GET usuario por ID
router.get('/usuarios/:id', async (req, res) => {
    const resultado = await usuariosModel.traerUsuario(req.params.id);
    if (resultado.length === 0) {
        res.status(404).send("Usuario no encontrado");
        return;
    }
    res.json(resultado[0]);
});

// POST login
router.post('/auth/login', async (req, res) => {
    const { usuario, password } = req.body;
    const resultado = await usuariosModel.validarUsuario(usuario, password);
    if (resultado.length === 0) {
        res.status(401).send("Credenciales inválidas");
        return;
    }
    res.json({ mensaje: "Inicio de sesión exitoso", usuario: resultado[0] });
});

// POST crear usuario
router.post('/usuarios', async (req, res) => {
    const { nombre_completo, usuario, password, rol } = req.body;
    await usuariosModel.crearUsuario(nombre_completo, usuario, password, rol);
    res.send("Usuario creado correctamente");
});

module.exports = router;
