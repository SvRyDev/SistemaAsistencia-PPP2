<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<style>
  * {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding: 0;
  }

  .carnet-card {
    position: relative;
    overflow: hidden;
    width: 85mm;
    height: 55mm;

    overflow: hidden;
  }

  #plantilla {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .barcode-content {
    position: absolute;
    top: 82px;
    left: 217px;
    width: 145px;
    height: 43px;

    transform: rotate(-90deg);
    overflow: hidden;
    /* importante para que el img absoluto se posicione dentro */
    position: relative;
  }

  .barcode-content img {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 90%;
    height: 100%;
    max-height: 80%;
    transform: translate(-50%, -50%);
  }


  .content-nombres {
    position: absolute;
    top: 60px;
    left: 105px;
    width: 150px;
    height: 39px;
    text-transform: uppercase;
    font-weight: bold;
    color: white;
    font-size: 12.8px;
    line-height: 1.2;
    padding-top: 4px;
    padding-bottom: 4px;
  }

  #anio {
    position: absolute;
    bottom: -5px;
    color: white;
    left: 7px;
    color: rgba(255, 255, 255, 0.29);
    font-size: 40px;
    font-weight: bold;
  }

  .detalle {
    position: absolute;
    top: 110px;
    left: 108px;
    width: 150px;
    color: white;
  }

  .content-d {
    margin-bottom: 5px;
    width: 100%;
  }

  .titulo-d {
    font-family: Arial, sans-serif;
    font-size: 9px;
  }

  .dato-d {
    font-family: Arial, sans-serif;
    font-size: 9px;
    text-transform: uppercase;
    margin-left: 9px;
    font-weight: bold;
  }

  .d-row {
    width: 100%;
  }

  .d-row .content-d {
    display: inline-block;
    width: 48%;
    vertical-align: top;
  }
</style>

<body>
  <div class="carnet-card">
    
    <img id="plantilla" src="<?= assets() ?>/img/static/plantilla-carnet.jpg" alt="">

<!--
    <img id="plantilla" src="C:\xampp\htdocs\SistemaAsistencia-PPP2\public\assets\img\static\plantilla-carnet.jpg"
      alt="" />
   -->
    <div class="barcode-content">
      <img src="data:image/png;base64,<?= $data['barcode'] ?>" alt="a" />
    </div>
    <div class="content-nombres">
      <p id="nombres_ape"><?= $data['name'] ?></p>
    </div>
    <p id="anio">2025</p>

    <div class="detalle">
      <div class="content-d">
        <p class="titulo-d">Codigo:</p>
        <p class="dato-d"><?= $data['student_id'] ?></p>
      </div>
      <div class="d-row">
        <div class="content-d">
          <p class="titulo-d">Grado:</p>
          <p class="dato-d">primero</p>
        </div>
        <div class="content-d">
          <p class="titulo-d">Sección:</p>
          <p class="dato-d">A</p>
        </div>
      </div>

      <div class="content-d">
        <p class="titulo-d">Emisión:</p>
        <p class="dato-d">15/03/2025</p>
      </div>
    </div>
  </div>
</body>

</html>