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
            <h1>ACTAS <i class="fa fa-box"></i></h1>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID OPERADOR</th>
                        <th>RAZON SOCIAL</th>
                        <th>CANT FORM 101</th>
                        <th>FORMULARIOS 101</th>
                        <th>PESO BRUTO</th>
                        <th>PESO NETO</th>
                        <th>FORM TOMA MUESTRA</th>
                        <th>PESO NETO TDM</th>
                        <th>DIFERENCIA</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tomaDeMuestras as $tomaDeMuestra)
                        <tr>
                            @if ($tomaDeMuestra->formulario()->count()>0)
                                <td>{{$tomaDeMuestra->formulario()->first()->id_operador_minero}}</td>
                            
                            <td>{{$tomaDeMuestra->formulario()->first()->operadorMinero()->first()->razon_social}}</td>
                            
                            <Td>{{$tomaDeMuestra->formulario()->where('estado_formulario','!=',0)->where('estado_formulario','!=',3)->count()}}</Td>

                            <td>
                                @foreach ($tomaDeMuestra->formulario()->get() as $formulario)
                                @if ($formulario->estado_formulario!=0 and $formulario->estado_formulario!=3)
                                    {{$formulario->num_form.', '}}
                                @endif
                                @endforeach
                            </td>
                            <td>
                                {{$tomaDeMuestra->formulario()->sum('peso_bruto')}}
                            </td>
                            <td>
                                @php
                                $sumaPesoNeto=0;
                                foreach($tomaDeMuestra->formulario()->get() as $formulario){
                                if(($formulario->estado_formulario!=0 and $formulario->estado_formulario!=3))
                                    $sumaPesoNeto=(($formulario->peso_bruto-$formulario->tara)*((1-$formulario->humedad*0.01)*(1-$formulario->merma*0.01)))+$sumaPesoNeto;
                                }
                                @endphp
                                {{$sumaPesoNeto}}
                            </td>
                            <td>
                                {{$tomaDeMuestra->acta}}
                            </td>
                            <td>
                                {{$tomaDeMuestra->peso_neto*1000}}
                            </td>
                            
                            <td>
                            {{$tomaDeMuestra->peso_neto*1000-$sumaPesoNeto}}
                            </td>
                            @endif
                            
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
