<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/horizontalreport.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report();

// Se verifica si existe un valor para el partido, de lo contrario se muestra un mensaje.
if (isset($_GET['idPartido'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/participaciones_partidos_data.php');
    require_once('../../models/data/partidos_data.php');

    // Se instancian las entidades correspondientes.
    $participacion = new ParticipacionesPartidosData();
    $partidos = new PartidosData();

    // Se establece el valor del partido, de lo contrario se muestra un mensaje.
    if ($participacion->setIdPartido($_GET['idPartido'])) {
        $partidos->setIdPartido($_GET['idPartido']);

        //Iniciamos el reporte, asi mismo mandamos el titulo del reporte
        $pdf->startReport('Participaciones en el partido');

        //Obtenemos la data del partido.
        $rowPartido = $partidos->readOne();
        //Asignamos la informacion del partidos en filas.
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(48, 8, $pdf->encodeString('Fecha y hora del partido:'), 0, 0, 'L');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(100, 8, $pdf->encodeString($rowPartido['fecha_partido']),  0, 0,'L');
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(40, 8, $pdf->encodeString('Cancha:'), 0, 0, 'R');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(50, 8, $pdf->encodeString($rowPartido['cancha_partido']),  0, 1,'L');

        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(12, 8, $pdf->encodeString('Rival:'), 0, 0, 'L');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(141, 8, $pdf->encodeString($rowPartido['nombre_rival']),  0, 0,'L');
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(40, 8, $pdf->encodeString('Resultado:'), 0, 0, 'R');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(50, 8, $pdf->encodeString($rowPartido['resultado_partido']. ' '. $rowPartido['tipo_resultado_partido']),  0, 1,'L');

        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(21, 8, $pdf->encodeString('Localidad:'), 0, 0, 'L');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(128, 8, $pdf->encodeString($rowPartido['localidad_partido']),  0, 0,'L');
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(40, 8, $pdf->encodeString('Jornada:'), 0, 0, 'R');
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(50, 8, $pdf->encodeString($rowPartido['nombre_jornada']),  0, 1,'L');

        //Se hace un salto de linea
        $pdf->ln(5);

        // Se verifica si el partido existe, de lo contrario se muestra un mensaje.
        if ($rowParticipacion = $participacion->participationReports()) {

            //Asignamos decoracion a la fila, color de bordes, de texto y el fondo de filas.
                $pdf->setTextColor(255, 255, 255);
                $pdf->setFillColor(2, 8, 135);
                $pdf->setDrawColor(2, 8, 135);
                $pdf->setFont('Arial', 'B', 11);

                //Creamos los titulos de la fila.
                $pdf->cell(90, 10, 'Jugador', 1, 0, 'C', 1);
                $pdf->cell(20, 10, 'Min.', 1, 0, 'C', 1);
                $pdf->cell(20, 10, 'Goles', 1, 0, 'C', 1);
                $pdf->cell(45, 10, $pdf->encodeString('Estado de ánimo'), 1, 0, 'C', 1);
                $pdf->cell(50, 10, $pdf->encodeString('Posición'), 1, 0, 'C', 1);
                $pdf->cell(25, 10, $pdf->encodeString('Puntuación'), 1, 1, 'C', 1);

                 // Se recorren los registros fila por fila.
                foreach ($rowParticipacion as $item) {
                    $pdf->setFont('Arial', '', 11);
                    // Establecer color de texto a negro
                    $pdf->setTextColor(0, 0, 0);
                    //Asignamos la data traida de participaciones partido a las filas.
                    $pdf->cell(90, 10, $pdf->encodeString($item['nombre_jugador'] . ' ' . $item['apellido_jugador']), 1, 0, 'C');
                    $pdf->cell(20, 10, $pdf->encodeString($item['minutos_jugados']. ' min'), 1, 0, 'C');
                    $pdf->cell(20, 10, $pdf->encodeString($item['goles']), 1, 0, 'C');
                    $pdf->cell(45, 10, $pdf->encodeString($item['estado_animo']), 1, 0, 'C');
                    $pdf->cell(50, 10, $pdf->encodeString($item['posicion']), 1, 0, 'C');
                    $pdf->cell(25, 10, $pdf->encodeString($item['puntuacion']), 1, 1, 'C');
                }

            } else {
            //Asignamos la fuente normal
                $pdf->setFont('Arial', '', 13);
                $pdf->cell(0, 80, $pdf->encodeString('Aún no has agregado participaciones en este partido'), 0, 1, 'C');
            }
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'Particiapciones por partidos.pdf');
        } else {
            print('Marca inexistente');
        }
} else {
    print('No se proporcionó un ID del partido.');
}

