<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

</head>
<body>
  <div class="modal fade" id="cargaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Datos de la carga:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="selectUsu" aria-label=".form-select-sm example">
                            <option selected>Seleccionar usuario</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="selectMat" aria-label=".form-select-sm example">
                            <option selected>Seleccionar materia</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline-success" id="guardarCarga">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <br />
    <h1 class="text-center text-primary"><u>How to Use FullCalendar in Laravel 10</u></h1>
    <br />
    <div id="calendar"></div>
</div>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var startDate; // Variable para almacenar la fecha de inicio
        var endDate; // Variable para almacenar la fecha de fin

        var calendar = $('#calendar').fullCalendar({
            editable: false,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDays) {
                $('#cargaModal').modal('show');
                startDate = moment(start).format('YYYY-MM-DD HH:mm:ss');
                endDate = moment(end).format('YYYY-MM-DD HH:mm:ss');
                calendar.unselect();
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: '/eventos',
            eventRender: function(event, element) {
                var eventTitle = event.title;
                if (event.end) {
                    eventTitle += ' - ' + moment(event.end).format('H:mm'); // Ajusta el formato según tus necesidades
                }
                element.find('.fc-title').html(eventTitle);
                element.find('.fc-title').append('<br/><span class="small">' + event.usuario + '</span>');
                element.find('.fc-title').append('<br/><span class="small">' + event.carrera + '</span>');
            }
        });

        // Cargar opciones de usuario y materia al abrir el modal
        $('#cargaModal').on('shown.bs.modal', function() {
            cargarOpcionesUsuario();
            cargarOpcionesMateria();
        });

        // Función para cargar opciones de usuario
        function cargarOpcionesUsuario() {
            $.ajax({
                url: '/obtener-usuarios',
                success: function(response) {
                    var selectUsu = $('#selectUsu');
                    selectUsu.empty();
                    selectUsu.append('<option selected>Seleccionar usuario</option>');
                    $.each(response, function(index, usuario) {
                        selectUsu.append('<option value="' + usuario.id + '">' + usuario.nombre + '</option>');
                    });
                }
            });
        }

        // Función para cargar opciones de materia
        function cargarOpcionesMateria() {
            var selectMat = $('#selectMat');
            selectMat.empty();
            selectMat.append('<option selected>Seleccionar materia</option>');

            $.getJSON('/obtener-materias', function(response) {
                $.each(response, function(index, materia) {
                    var option = $('<option>').val(materia.id).text(materia.nameMat + ' - ' + materia.carrera.nameCarr);
                    selectMat.append(option);
                });
            });
        }

        // Guardar carga
        $('#guardarCarga').on('click', function() {
            var usuarioId = $('#selectUsu').val();
            var materiaId = $('#selectMat').val();

            // Hacer algo con los datos seleccionados (ejemplo: enviar por Ajax para guardar en la base de datos)
            $.ajax({
                url: '/guardar-carga',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    usuarioId: usuarioId,
                    materiaId: materiaId
                },
                success: function(response) {
                    // Manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el modal
                    $('#cargaModal').modal('hide');
                }
            });
        });

        // Depuración de la respuesta de eventos
        $.ajax({
            url: '/eventos',
            success: function(response) {
                console.log(response);
            }
        });
    });
</script>


</body>
</html>
