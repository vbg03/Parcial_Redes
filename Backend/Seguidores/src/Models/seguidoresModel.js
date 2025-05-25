const mysql = require('mysql2/promise');

const conexion = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',  // Ajusta según tu configuración
    database: 'seguidoresMS'
});

async function crearRelacion(usuarioP, usuarioS) {
    await conexion.query(
        'INSERT INTO seguidores (usuarioP, usuarioS) VALUES (?, ?)',
        [usuarioP, usuarioS]
    );
}

async function obtenerSeguidos(usuarioS) {
    const [rows] = await conexion.query(
        'SELECT usuarioP FROM seguidores WHERE usuarioS = ?',
        [usuarioS]
    );
    return rows;
}

module.exports = {
    crearRelacion,
    obtenerSeguidos
};
