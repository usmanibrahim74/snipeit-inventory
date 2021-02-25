<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

{{ Form::open([
            'target' => '_blank',
          'method' => 'POST',
          'route' => ['hardware/bulkedit'],
          'class' => 'form-inline',
           'id' => 'bulkForm']) }}

<input type="hidden" name="bulk_actions" value="labels">
<input type="hidden" name="ids[]" value="{{ $id }}">
{{ Form::close() }}

<script src="{{ asset('/js/jquery.js') }}"></script>
<script>
    var myForm = document.getElementById('bulkForm');
    myForm.onsubmit = function(e) {
        var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300,left = 312,top = 234');
        this.target = 'Popup_Window';
    };
    $(document).ready(function(){
        $('#bulkForm').submit();
        window.location.replace("{{ route('hardware.index') }}")
    });
</script>
</body>
</html>
