<?php //dd(asset("hardware/".$assets[0]->id.'/barcode')) ?>
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

<?php
$settings->labels_width = $settings->labels_width - $settings->labels_display_sgutter;
$settings->labels_height = $settings->labels_height - $settings->labels_display_bgutter;
// Leave space on bottom for 1D barcode if necessary
$qr_size = ($settings->alt_barcode_enabled=='1') && ($settings->alt_barcode!='') ? $settings->labels_height - .3 : $settings->labels_height - .3;

?>

<style>
    @page {
        margin:0px;
        padding: 0;
    }
    *{
        box-sizing: border-box;
    }
    body{
        margin: 0;
        padding: 0;
    }
    body{
        font-family: arial, helvetica, sans-serif;
        font-size: {{ $settings->labels_fontsize }}pt;
        display: none;
    }
    .label {
        position: relative;
        width: {{ $settings->labels_width * 96 }}px;
        height: {{ $settings->labels_height * 96 }}px;
        padding: 0 9.6px !important;
        /*border: 1px solid;*/
    }
    .barcode_container{
        /*position: absolute;*/
        width: 100%;
        /*bottom: 9.6px;*/
        /*left: 0;*/
        text-align: center;
    }
    img.barcode {
        /*width: 40%;*/
    }
    .page-break{
        page-break-after: always;
    }
    p{
        margin: 0;
        padding: 0;
    }
</style>

@foreach ($assets as $asset)

    <?php $count++; ?>
    <div class="label">
        <div>

            @if ($settings->qr_text!='')
                <strong>{{ $settings->qr_text }}</strong>
                <br>

            @endif
            @if (($settings->labels_display_company_name=='1') && ($asset->company))
                <p>Company: {{ $asset->company->name }}</p>
            @endif
            @if (($settings->labels_display_name=='1') && ($asset->name!=''))
                <p>Name: {{ $asset->name }}</p>
            @endif
            @if (($settings->labels_display_tag=='1') && ($asset->asset_tag!=''))
                <p>Asset Tag: {{ $asset->asset_tag }}</p>
            @endif
            @if (($settings->labels_display_serial=='1') && ($asset->serial!=''))
                <p>Serial: {{ $asset->serial }}</p>
            @endif
            @if (($settings->labels_display_model=='1') && ($asset->model->name!=''))
                <p>Model: {{ $asset->model->name }} {{ $asset->model->model_number }}</p>
            @endif

            @if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))
                <div class="barcode_container">
                    @php
                        if(false){
                        $path = asset("hardware/".$assets[0]->id.'/barcode');
                        $path = file_get_contents($path);

                        $content = file_get_contents(asset($path));
                        $base64 = "data:image/png;base64,".base64_encode($content);
                        }

                        $base64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI8AAAAUAQMAAACODPxQAAAABlBMVEX///8AAABVwtN+AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAIUlEQVQYlWP4efCPPDv7xw81P+zP8Tc/sD9XxzAqRB8hAI4kzSlDw9sbAAAAAElFTkSuQmCC";

                    @endphp
                    <img src="{{ $base64 }}" class="barcode">
                </div>

            @endif
        </div>

    </div>

    @if($asset->model->fieldset && count($asset->model->fieldset->fields))
    <div class="label custom-label">
        <br>
{{--        @if ($settings->qr_text!='')--}}
{{--            <p>{{ $settings->qr_text }}</p>--}}
{{--            <br>--}}
{{--        @endif--}}
{{--        @if (($settings->labels_display_company_name=='1') && ($asset->company))--}}
{{--            <p>Company: {{ $asset->company->name }}</p>--}}
{{--        @endif--}}
{{--        @if (($settings->labels_display_name=='1') && ($asset->name!=''))--}}
{{--            <p>Name: {{ $asset->name }}</p>--}}
{{--        @endif--}}
{{--        @if (($settings->labels_display_tag=='1') && ($asset->asset_tag!=''))--}}
{{--            <p>Asset tag: {{ $asset->asset_tag }}</p>--}}
{{--        @endif--}}
{{--        @if (($settings->labels_display_serial=='1') && ($asset->serial!=''))--}}
{{--            <p>Serial: {{ $asset->serial }}</p>--}}
{{--        @endif--}}
{{--        @if (($settings->labels_display_model=='1') && ($asset->model->name!=''))--}}
{{--            <p>Model: {{ $asset->model->name }} {{ $asset->model->model_number }}</p>--}}
{{--        @endif--}}

        @if($asset->model->fieldset && count($asset->model->fieldset->fields))
            @foreach($asset->model->fieldset->fields as $field)

                <p>{{ $field->name }}: {{ $asset->{$field->db_column_name()} }}</p>

            @endforeach
        @endif


{{--        @if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))--}}
{{--            <div class="barcode_container">--}}
{{--                <img src="{{ $base64 }}" class="barcode">--}}
{{--            </div>--}}
{{--        @endif--}}




    </div>

    @endif
    <button class="print-btn">print</button>
@endforeach

<script src="{{ asset('/js/jquery.js') }}"></script>
<script src="{{ asset('/js/jspdf.min.js') }}"></script>

<script>

    function convertImageToCanvas(image) {
        var canvas = document.createElement("canvas");
        canvas.width = image.width;
        canvas.height = image.height;
        canvas.getContext("2d").drawImage(image, 0, 0);

        return canvas;
    }
    function convertCanvasToImage(canvas) {
        var image = new Image();
        image.src = canvas.toDataURL("image/jpeg");
        return image;
    }
    $(document).ready(function () {
        var doc = new jsPDF({
            orientation: "landscape",
            unit: "in",
            format: [{{ $settings->labels_width }}, {{ $settings->labels_height }}]
        })



        var labels = $('.label');
        labels.each(function (i,item) {
            var lineX = 0.05;
            var lineY = 0.1;
            var increment = 0.15
            var isCustomLabel = true;
            if(!$(item).hasClass('custom-label')){
                isCustomLabel = false;
            }
            doc.setFontType('bold');
            $(item).find('p').each(function (i,item) {
                var text = $(item).text();
                if(text.toLowerCase().includes('asset tag') && !isCustomLabel){
                    doc.setFontSize({{ $settings->labels_fontsize*2.5 }});
                    lineY +=0.09;
                }else if(text.toLowerCase().includes('model') && !isCustomLabel ){
                    text = text.replace("Model: ","");
                    doc.setFontSize({{ $settings->labels_fontsize*2 }});
                    lineY +=0.04;

                }else{
                    doc.setFontSize({{ $settings->labels_fontsize*1.2 }});
                }
                doc.text(lineX, lineY, text);
                lineY += increment;

            })
            var image = $(item).find('img')[0];
            if(image){

                var canvas = convertImageToCanvas(image);
                var imgData = convertCanvasToImage(canvas);
                doc.addImage(imgData, 'JPEG', 0.05, 0.7, 1.40, 0.2);
            }
            if(i < labels.length - 1){
                doc.addPage()
            }


        })

        doc.save("a4.pdf");
        setTimeout(function () {

            window.top.close();
        },10)


    })
</script>
</body>
</html>
