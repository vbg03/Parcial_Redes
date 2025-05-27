const mysql = require('mysql2/promise');

const conexion = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',  // Ajusta según tu configuración
    database: 'mensajesMS'
});

async function crearMensaje(id_usuario, contenido) {
    const resultado = await conexion.query(
        'INSERT INTO mensajes (id_usuario, contenido) VALUES (?, ?)',
        [id_usuario, contenido]
    );
    return resultado;
}

async function obtenerMensajesPorUsuario(id_usuario) {
    const [rows] = await conexion.query(
        'SELECT * FROM mensajes WHERE id_usuario = ? ORDER BY fecha_creacion DESC',
        [id_usuario]
    );
    return rows;
}

async function eliminarMensaje(id) {
    await conexion.query('DELETE FROM mensajes WHERE id = ?', [id]);
}

module.exports = {
    crearMensaje,
    obtenerMensajesPorUsuario,
    eliminarMensaje
};
