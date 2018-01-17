<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}" />
    <meta id="homepage" name="homepage" content="{{ url('/') }}" />
    
    <meta name="author" content="">

    <title>{{ Settings::value('site-title') }} | {{ Settings::value('site-tagline') }}</title>
    <meta name="description" content="{{ Settings::value('site-description') }}">

    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    @if(Auth::check())
        @yield('admin-bar', view('magis.partials.admin-bar'))
    @endif
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.html">Start Bootstrap</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ url('pages/about') }}">About</a>
                    </li>
                    <li>
                        <a href="{{ url('posts') }}">Blog</a>
                    </li>
                    <li>
                        <a href="{{ url('contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    @yield('cover', view('magis.partials.samples.cover'))

    @yield('content')

    @yield('foundation', view('magis.partials.imports.public-foundation'))
    
    <script src="{{ asset('Magis/AdminLTE-2.3.7/plugins/fastclick/fastclick.min.js') }}"></script>
    <script src="{{ asset('Magis/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('Magis/clean-blog/clean-blog.js') }}"></script>

    @yield('script')

</body>
</html>
