const express = require('express');
const morgan = require('morgan');
const usuariosController = require('./Controllers/usuariosController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(usuariosController);
app.use('/', usuariosController); // 👉 permite rutas como /usuarios y /auth

app.listen(3001, () => {
    console.log('✅ Microservicio de Usuarios corriendo en el puerto 3001');
});
