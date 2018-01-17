<style media="screen">
    html {
        margin-top: 55px !important;
    }
    * html body {
        margin-top:40px !important;
    }
    #magis-admin-bar {
        background-color:black;
        width:100%;
        color:white;
        position:fixed;
        top:0px;
        left:0px;
        height:54px;
        z-index:10000;
    }
    #magis-admin-bar img {
        height:54px;
    }
    #magis-admin-bar .btn-link {
        color: white;
        margin-top: 10px;
    }
    #magis-admin-bar small {
        font-size: 12px;
        font-style: italic;
        margin-top: 20px;
        margin-right: 10px;
    }
    .navbar-default {
        top: 55px;
    }
    #magis-submit{
        height: 54px;
        border-radius: 0px;
        font-size: 16px;
        background-color: #0d47a1!important;
        border-color: #0d47a1;
    }
    @media ( max-width: 767px){
        #magis-admin-bar small {
            display: none;
        }
    }
    
</style>
<div id="magis-admin-bar">
    <?php $resource = $resource ?? Request::segment(1); ?>
    <img src="{{ asset('magis/img/avatar/logo_promo2.png') }}" class="img-responsive pull-left" />
    <a href="{{ url($resource.'/manage') }}" type="button" class="btn btn-link pull-left">Back to Admin</a>
    @if(isset($item))
        @can('update', $item)
            <button type="button" id="magis-submit" class="btn btn-primary pull-right">Save</button>
            <small class="pull-right">The content below is editable. Click "Save" to save changes.</small>
        @endcan
    @endif
</div>