const { Router } = require('express');
const router = Router();
const usuariosModel = require('../Models/usuariosModel');
const jwt = require('jsonwebtoken');

const SECRET_KEY = "miprimertokenseguro123"; // ⚠️ En producción va en .env

// ------------------------
// GET: Todos los usuarios
// ------------------------
router.get('/usuarios', async (req, res) => {
    const resultado = await usuariosModel.traerUsuarios();
    res.json(resultado);
});

// ------------------------
// GET: Usuario por ID
// ------------------------
router.get('/usuarios/:id', async (req, res) => {
    const resultado = await usuariosModel.traerUsuario(req.params.id);
    if (resultado.length === 0) {
        return res.status(404).send("Usuario no encontrado");
    }
    res.json(resultado[0]);
});

// ------------------------
// POST: Login con JWT
// ------------------------
router.post('/auth/login', async (req, res) => {
    const { usuario, password } = req.body;
    const resultado = await usuariosModel.validarUsuario(usuario, password);
    if (resultado.length === 0) {
        return res.status(401).json({ mensaje: "Credenciales inválidas" });
    }

    const user = resultado[0];

    // Generar token con info útil
    const token = jwt.sign(
        { id: user.id, usuario: user.usuario, rol: user.rol },
        SECRET_KEY,
        { expiresIn: '1h' }
    );

    res.json({
        mensaje: "Inicio de sesión exitoso",
        token: token,
        usuario: {
            id: user.id,
            nombre_completo: user.nombre_completo,
            usuario: user.usuario, 
            rol: user.rol
        }
    });
});

// ------------------------
// Middleware: verificar token
// ------------------------
function verificarToken(req, res, next) {
    const header = req.headers['authorization'];
    if (!header) return res.status(401).send("Token requerido");

    const token = header.split(" ")[1]; // "Bearer <token>"
    if (!token) return res.status(401).send("Token inválido");

    jwt.verify(token, SECRET_KEY, (err, decoded) => {
        if (err) return res.status(403).send("Token no válido");
        req.user = decoded; // Guardar usuario en req
        next();
    });
}

// ------------------------
// Middleware: verificar admin
// ------------------------
function verificarAdmin(req, res, next) {
    if (req.user.rol !== 'admin') {
        return res.status(403).send("Solo el administrador puede crear usuarios");
    }
    next();
}

// ------------------------
// POST: Crear usuario (protegido por token + admin)
// ------------------------
router.post('/usuarios', verificarToken, verificarAdmin, async (req, res) => {
    const { nombre_completo, usuario, password, rol } = req.body;
    await usuariosModel.crearUsuario(nombre_completo, usuario, password, rol);
    res.send("Usuario creado correctamente");
});

module.exports = router;
