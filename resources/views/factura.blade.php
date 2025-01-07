<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin-top: -10px !important;
            margin-left: -20px !important;
            margin-right: 20px !important; /* Elimina los márgenes de la página */
            padding: 0; /* Elimina el padding de la página */
        }
        .container {
            width: 100%;
            padding: 20px; /* Reduce el padding interno */
            margin: 0; /* Elimina márgenes adicionales */
            border: solid 1px black;
        }
        .info,
        .totals,
        .footer,
        .payment-options {
            margin: 0 0 10px 0; /* Reduce margen inferior */
        }
        .payment-options {
            font-size: 10px;
            margin-top: 5px;
        }
        .details{
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px; /* Reduce el espacio entre tablas */
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th,
        .details td {
            border: 1px solid black;
            padding: 4px; /* Reduce el padding interno */
            text-align: left;
        }
        .totals-table th,
        .totals-table td {
            border: 1px solid black;
            padding: 4px; /* Reduce el padding interno */
            text-align: left;
        }
        .totals-table th,
        .totals-table td {
            text-align: right;
        }
        .company-info {
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
        }
        .footer {
            text-align: right;
            font-size: 15px;
            margin-top: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between; /* Espacia los elementos a los extremos */
            align-items: center; /* Centra verticalmente los elementos */
            margin-bottom: 10px; /* Espaciado inferior */
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Crea dos columnas de igual tamaño */
            align-items: center; /* Centra verticalmente los elementos en la cuadrícula */
            margin-bottom: 10px; /* Espaciado inferior */
        }
        .grid-item {
            padding: 0 5px; /* Espaciado lateral */
        }
        .text-center {
            text-align: center !important;
        }
    </style>
    
</head>

<body>
    <div class="container">
        <!-- Encabezado de la Factura -->
        <table width="100%" style="border-collapse: collapse;">
    <tr>
        <!-- Columna izquierda con la imagen y datos de la empresa -->
        <td width="60%" style="text-align: center;">
            <img width="80%" height="70px" src="{{ public_path('img/tst.jpg') }}" alt="">
            <p class="bg-primary text-white" style="margin: 0; padding: 5px;"><strong><i>Adrian Mercado Subastas S.A</i></strong></p>
            <p style="margin: 0;"><strong><i>Olga Cossettini 731, Piso 3</i></strong></p>
            <p style="margin: 0;"><strong><i>CABA &#40;C1107CDA Tel: +54 11 3984-7400&#41;</i></strong></p>
        </td>

        <!-- Columna derecha con los datos de la factura -->
        <td width="40%" style="text-align: left; vertical-align: top;">
            <h2>Factura</h2>
            <p><strong>Punto de Venta:</strong> {{ $punto_venta }} <strong>Comp. Nro. :</strong> {{ $comp_nro }}</p>
            <p><strong>Fecha de Emisión:</strong> {{ $fecha_emision }}</p>
            <p><strong>Cuit:</strong> 30-71803361-2</p> 
            <p><strong>Ingresos Brutos:</strong> 30-71803361-2</p>
            <p><strong>Fecha de Inicio de Actividades: :</strong>2/12/2022</p>
        </td>
    </tr>
</table>


        <hr style="border: none; border-top: 1px solid black; width: 100%; margin: 10px 0;">
        <!-- Información del Cliente y de la Empresa -->
        <div class="info">
            <div class="grid">
                <div class="grid-item" style="text-align: left;">
                    <strong>Apellido y Nombre / Razón Social:</strong> {{ $razon_social }}
                </div>
                <div class="grid-item" style="text-align: right;">
                    <strong>Teléfono:</strong> {{ $telefono }}
                </div>
                <div class="grid-item" style="text-align: left;">
                    <strong>Domicilio:</strong> {{ $domicilio }} {{ $nro_domicilio }} ({{ $code_postal }})
                </div>
                <div class="grid-item" style="text-align: right;">
                    <strong>Email:</strong> {{ $email }}
                </div>
                <div class="grid-item" style="text-align: left;">
                    <strong>CUIT:</strong> {{ $cuit }}
                </div>
                <div class="grid-item" style="text-align: right;">
                    <strong>Condición de Venta:</strong> {{ $condicion_venta }}
                </div>
                <div class="grid-item" style="text-align: left;">
                    <strong>Cond. frente al IVA:</strong> {{ $condicion_iva }}
                </div>
            </div>
        </div>
        <hr style="border: none; border-top: 1px solid black; width: 100%; margin: 10px 0;">

        <div class="info-subasta">
            <p><strong><i>Por la subasta efectuada en:</i></strong> {{ $ubi_subasta }}</p>
            <p><strong><i>De fecha:</i></strong> {{ $fecha_subasta }}  <strong><i>ID:</i></strong> {{ $id_subasta }}</p>
        </div>
        <hr style="border: none; border-top: 1px solid black; width: 100%; margin: 10px 0;">

        <!-- Detalles de los Productos -->
        <table class="details">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Producto / Servicio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto['lote'] }}</td>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>{{ number_format($producto['subtotal'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales e Impuestos -->
        <table class="totals-table">
            <thead>
                <tr>
                    <th>Neto 21%</th>
                    <th>Neto 10.5%</th>
                    <th>IVA 21%</th>
                    <th>IVA 10.5%</th>
                    <th>Uso Plataforma</th>
                    <th>Gs. Adm.</th>
                    <th>Importe Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($neto_21, 2, ',', '.') }}</td>
                    <td>{{ number_format($neto_10_5, 2, ',', '.') }}</td>
                    <td>{{ number_format($iva_21, 2, ',', '.') }}</td>
                    <td>{{ number_format($iva_10_5, 2, ',', '.') }}</td>
                    <td>{{ number_format($uso_plataforma, 2, ',', '.') }}</td>
                    <td>{{ number_format($gs_adm, 2, ',', '.') }}</td>
                    <td><strong>{{ number_format($importe_total, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Opciones de Pago -->
        <table width="100%" style="border-collapse: collapse; margin-top: 10px;">
            <tr>
                <!-- Columna izquierda: QR de AFIP -->
                <td width="20%" style="text-align: center; vertical-align: top;">
                    <img src="{{ $qr_path }}" alt="QR AFIP" style="width: 100%; max-width: 150px; height: auto;">
                </td>

                <!-- Columna derecha: Opciones de pago -->
                <td width="80%" style="text-align: left; vertical-align: top;">
                    <strong>
                        <p style="margin: 0;"><i>Opciones para cancelar esta factura:</i></p>
                        <p style="margin: 0;">
                            <i>Depósito/Transferencia Bancaria: {{ $opciones_pago['transferencia_bancaria']['banco'] }}.<br>
                            Nro. de Cuenta: {{ $opciones_pago['transferencia_bancaria']['cuenta'] }} - 
                            CBU N°: {{ $opciones_pago['transferencia_bancaria']['cbu'] }} - 
                            Alias: {{ $opciones_pago['transferencia_bancaria']['alias'] }}</i>
                        </p>
                        <p style="margin: 0;"><i>{{ $opciones_pago['cheques'] }}</i></p>
                    </strong>
                </td>
            </tr>
        </table>


        <!-- Pie de Página con CAE -->
        <div class="footer">
            <p><strong><i>CAE N°:</i></strong> {{ $cae }} <br> <strong><i>Fecha Vto. CAE:</i></strong> {{ $fecha_vto_cae }}</p>
        </div>
    </div>
</body>

</html>
