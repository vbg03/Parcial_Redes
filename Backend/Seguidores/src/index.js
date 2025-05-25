const express = require('express');
const morgan = require('morgan');
const seguidoresController = require('./Controllers/seguidoresController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(seguidoresController);

app.listen(3003, () => {
    console.log('✅ Microservicio de Seguidores corriendo en el puerto 3003');
});
