const mysql = require('mysql2/promise');

const conexion = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',  // Reemplaza por tu password real si tienes
    database: 'usuariosMS'
});

async function traerUsuarios() {
    const [rows] = await conexion.query('SELECT id, nombre_completo, usuario, rol FROM usuarios');
    return rows;
}

async function traerUsuario(id) {
    const [rows] = await conexion.query('SELECT * FROM usuarios WHERE id = ?', [id]);
    return rows;
}

async function validarUsuario(usuario, password) {
    const [rows] = await conexion.query('SELECT * FROM usuarios WHERE usuario = ? AND password = ?', [usuario, password]);
    return rows;
}

async function crearUsuario(nombre_completo, usuario, password, rol) {
    await conexion.query('INSERT INTO usuarios VALUES (null, ?, ?, ?, ?)', [nombre_completo, usuario, password, rol]);
}

async function eliminarUsuario(id) {
    await conexion.query('DELETE FROM usuarios WHERE id = ?', [id]);
}

module.exports = {
    traerUsuarios,
    traerUsuario,
    validarUsuario,
    crearUsuario,
    eliminarUsuario
};
