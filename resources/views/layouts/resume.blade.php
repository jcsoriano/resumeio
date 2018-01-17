<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}" />
    <meta id="homepage" name="homepage" content="{{ url('') }}" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

    @include('partials.meta')

    <link href="{{ asset('css/myresumes.css') }}" rel="stylesheet" />
    <link rel="stylesheet" media="print" href="{{ asset('css/print.css') }}">
   
</head>
<body>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-92403642-1', 'auto');
      ga('send', 'pageview');
    </script>
    <div id="app">
        <div id="mdb-lightbox-ui"></div>
        @yield('content')
    </div>
    @yield('main-script')

    <script>
        Dropzone.autoDiscover = false;
    </script>

    <!-- JQuery -->
    {{-- <script type="text/javascript" src="{{ asset('resumeio/js/jquery-3.1.1.min.js') }}"></script> --}}

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="{{ asset('resumeio/js/tether.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resumeio/js/mdb.min.js') }}"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.nav-link').removeClass('waves-effect waves-light');
            $("#mdb-lightbox-ui").load("{{ asset('resumeio/mdb-addons/mdb-lightbox-ui.html') }}");
        });
    </script>



    <script type="text/javascript">
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "3000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
    </script>

    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58a0badf7cbf160f"></script>
<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("https://assets.zendesk.com/embeddable_framework/main.js","magissolutions.zendesk.com");
/*]]>*/</script>


</body>
</html>
