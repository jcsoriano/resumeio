<?php $resource = $resource ?? Request::segment(1); ?>
<style>
    .note-popover .btn {
        padding:5px 10px;
    }
</style>
<script>
    var jSummernote = $('#summernote-{{ $item->slug }}');
    jSummernote.summernote({
        airMode: true,
        focus: true,
        callbacks: {
            onImageUpload: function(files) {
                var file = files[0];
                var formData = new FormData();
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('path', '{{ $resource }}/{{ $item->id }}');
                $.ajax({
                    url: '{{ url('upload/photo/public') }}',
                    data: formData, 
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        jSummernote.summernote('insertImage', url(result.path), function($image) {
                            $image.attr('src', url(result.path));
                        });
                    },
                    error: function(error) {
                        console.log(error);
                        $.growl.error({ 
                            title: 'Error',
                            message: 'Something went wrong, check your internet connection and refresh this page.' 
                        });
                    }
                });
            },
        }
    });
    $('#magis-submit').click(function() {
        $.post('{{ url($item->getUrl()) }}', {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            name: '{{ $item->name }}',
            content: $('#summernote-{{ $item->slug }}').summernote('code')
        }, function(result, status) {
            if(status == 'success') {
                if(result.status == 'success') {
                    $.growl.notice({ 
                        title: 'Success',
                        message: 'Content has been saved' 
                    });
                } else {
                    console.log(result);
                }
            } else {
                $.growl.error({ 
                    title: 'Error',
                    message: 'Something went wrong, check your internet connection and refresh this page.' 
                });
            }
        });
    });
</script>