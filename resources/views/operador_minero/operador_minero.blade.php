<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{env("APP_NAME")}}">
    <meta name="author" content="Parzibyte">
    <title>@yield("titulo") - {{env("APP_NAME")}}</title>
    <link href="{{url("/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{url("/css/all.min.css")}}" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            /*Para la barra inferior fija*/
            padding-bottom: 70px;
        }
    </style>
</head>
<style>
    .estado1 {background-color : #961007 !important;color: #ffffff }
    .estado2 {background-color : #f4f48a !important; }
  </style>
<body>
    <div class="row">
        <div class="col-12">
            <h1>OPERADORES MINEROS <i class="fa fa-box"></i></h1>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>RAZON SOCIAL</th>
                        <th>REPRESENTANTE LEGAL</th>
                        <TH>TIPO</TH>
                        <th>EMAIL</th>
                        <th>FECHA EXP NIM</th>
                        <th>FECHA EXP SEPREC</th>
                        <th>FECHA EXP IDOM</th>
                        <th style="width:100px">OBSERVACIONES</th>
                        <th>USUARIOS</th>
                        <th>ESTADO USUARIOS</th>
                        <th>NOTIFICACION</th>
                        <th>Mensajes enviados</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td>{{$producto->id_operador_minero}}</td>
                            <td>{{$producto->razon_social}}</td>
                            <td>{{$producto->nombre_rep_legal}}</td>
                            <TD>{{$producto->actor_minero}}</TD>
                            <td>{{$producto->email_op_min}}</td>
                            @if($producto->fecha_exp_nim < now())
                            <td class="estado1">{{$producto->fecha_exp_nim.'   dias->'.round((strtotime($producto->fecha_exp_nim)-strtotime(now()))/86400)}}</td>
                            @elseif($producto->fecha_exp_nim < date('Y-m-d', strtotime(now(). ' + 5 days')))
                                <td class="estado2">{{$producto->fecha_exp_nim.'   dias->'.round((strtotime($producto->fecha_exp_nim)-strtotime(now()))/86400)}}</td>
                            @else
                                <td >{{$producto->fecha_exp_nim.'   dias->'.round((strtotime($producto->fecha_exp_nim)-strtotime(now()))/86400)}}</td>
                            @endif

                            @if($producto->fecha_exp_funda < now() and $producto->actor_minero==3 )
                            <td class="estado1">{{$producto->fecha_exp_funda.'   dias->'.round((strtotime($producto->fecha_exp_funda)-strtotime(now()))/86400)}}</td>
                            @elseif($producto->fecha_exp_funda < date('Y-m-d', strtotime(now(). ' + 5 days')) and $producto->fecha_exp_funda==3)
                                <td class="estado2">{{$producto->fecha_exp_funda.'   dias->'.round((strtotime($producto->fecha_exp_funda)-strtotime(now()))/86400)}}</td>
                            @else
                                <td >{{$producto->fecha_exp_funda.'   dias->'.round((strtotime($producto->fecha_exp_funda)-strtotime(now()))/86400)}}</td>
                            @endif

                            @if($producto->fecha_expiracion < now())
                            <td class="estado1">{{$producto->fecha_expiracion.'   dias->'.round((strtotime($producto->fecha_expiracion)-strtotime(now()))/86400)}}</td>
                            @elseif($producto->fecha_expiracion < date('Y-m-d', strtotime(now(). ' + 5 days')))
                                <td class="estado2">{{$producto->fecha_expiracion.'   dias->'.round((strtotime($producto->fecha_expiracion)-strtotime(now()))/86400)}}</td>
                            @else
                                <td >{{$producto->fecha_expiracion.'   dias->'.round((strtotime($producto->fecha_expiracion)-strtotime(now()))/86400)}}</td>
                            @endif
                            <td>{{$producto->observaciones}}</td>

                            <td>{{$producto->usuario()->count()}}</td>
                            @if($producto->usuario()->where('estado_usuario','=','1')->count()==0)
                            <td >
                                    <span class="d-flex justify-content-center badge badge-danger">Deshabilitado</span>
                            </td>
                            @else
                            <td>
                                <span class="d-flex justify-content-center badge badge-success">Activo</span>
                            </td>
                            @endif
                            <td>
                            <form id="notificar" action="{{route('operadores.notificar',$producto->id_operador_minero)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-warning">Enviar</button>
                            </form>
                            </td>
                            <td>{{$producto->email()->count()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
