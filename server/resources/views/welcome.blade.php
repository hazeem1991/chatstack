<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chat App Log</title>

        <!-- Fonts -->
        {{-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> --}}
        <link href="{{asset("public/css/app.css")}}" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            tr:nth-child(even){background-color: #f2f2f2}
            .m-b-md {
                margin-bottom: 30px;
            }
            pre.sf-dump, pre.sf-dump .sf-dump-default{
                background:#fff!important;
            }
        </style>
    </head>
    <body>
        <div class="full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel Chat App Log
                </div>
                <div class="container">
                    <table class="table table-hover">
                        <thead>
                            <th>id</th>
                            <th>user</th>
                            <th>endpoint</th>
                            <th>method</th>
                            <th>request</th>
                            <th>response</th>
                            <th>created_at</th>
                        </thead>
                        <tbody>
                        @foreach ($log->items() as $l )
                            <tr>
                                <td>{{$l->id}}</td>
                                <td>{{$l->user}}</td>
                                <td>{{$l->endpoint}}</td>
                                <td>{{$l->method}}</td>
                                <td style="text-align:left">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalRequest{{$l->id}}">
                                    View Request Data
                                    </button>
                                   
                                    <div class="modal fade" id="ModalRequest{{$l->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalRequest{{$l->id}}" aria-hidden="true" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-body">
                                                <?dump(json_decode($l->request,true))?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:left">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalResponse{{$l->id}}">
                                    View Response Data
                                    </button>
                                   
                                    <div class="modal fade" id="ModalResponse{{$l->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalResponse{{$l->id}}" aria-hidden="true" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-body">
                                                <?dump(json_decode($l->response,true))?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$l->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="links">
                    {!!$log->links()!!}
                </div>
            </div>
        </div>
    </body>
    <script src="{{ asset('public/js/app.js') }}"></script>
</html>
