const express = require('express');
const morgan = require('morgan');
const mensajesController = require('./Controllers/mensajesController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(mensajesController);

app.listen(3002, () => {
    console.log('✅ Microservicio de Mensajes corriendo en el puerto 3002');
});
