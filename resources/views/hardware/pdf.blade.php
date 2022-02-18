<?php //dd(asset("hardware/".$assets[0]->id.'/barcode')) ?>


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
    .labels-container{
        font-family: arial, helvetica, sans-serif;
        font-size: {{ $settings->labels_fontsize }}pt;
        /*display: none;*/
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
                <p>SID: {{ $asset->asset_tag }}</p>
            @endif

            @if (($settings->labels_display_model=='1') && ($asset->model->name!=''))
                <p>Model: {{ $asset->model->manufacturer->name }} {{ $asset->model->name }}
{{--                    {{ $asset->model->model_number }}--}}
                </p>
            @endif
            @if (($settings->labels_display_serial=='1') && ($asset->serial!=''))
                <p>Serial: {{ $asset->serial }}</p>
            @endif

            @if (($asset->model && $asset->model->category))
                <p>Category: {{ $asset->model->category->name }}</p>
            @endif

            @if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))
                <div class="barcode_container">
                    @php
                        if(true){
                        $path = asset("hardware/".$asset->id.'/barcode');
                        $path = file_get_contents($path);

                        $content = file_get_contents(asset($path));
                        $base64 = "data:image/png;base64,".base64_encode($content);
                        }else{
                            $base64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI8AAAAUAQMAAACODPxQAAAABlBMVEX///8AAABVwtN+AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAIUlEQVQYlWP4efCPPDv7xw81P+zP8Tc/sD9XxzAqRB8hAI4kzSlDw9sbAAAAAElFTkSuQmCC";
                        }


                    @endphp
                    <img src="{{ $base64 }}" class="barcode">
                </div>

            @else

                <div class="barcode_container">
                    @php
                        if(true){

                        $path = asset("hardware/".$asset->id.'/barcode');

                        $path = file_get_contents($path);
                        $content = file_get_contents(asset($path));
                        $base64 = "data:image/png;base64,".base64_encode($content);
                        }else{
                            $base64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUAQMAAAC3R49OAAAABlBMVEX///8AAABVwtN+AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAOUlEQVQImWM4c+YAAwh/sDEA4wPGB8D4PLMBGB/mOQDGBz5/AOP/QDYIHwbJAfEZBgYw/v//AwgDAFSxIi0id5XNAAAAAElFTkSuQmCC";
                        }


                    @endphp
                    <img src="{{ $base64 }}" class="barcode">
                </div>
            @endif

            @if($asset->model->fieldset && count($asset->model->fieldset->fields))
                @foreach($asset->model->fieldset->fields as $field)

                    <p>{{ $field->name }}: {{ $asset->{$field->db_column_name()} }}</p>

                @endforeach
            @endif


        </div>

    </div>

{{--    @if($asset->model->fieldset && count($asset->model->fieldset->fields))--}}
{{--    <div class="label custom-label">--}}
{{--        <br>--}}
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

{{--        @if($asset->model->fieldset && count($asset->model->fieldset->fields))--}}
{{--            @foreach($asset->model->fieldset->fields as $field)--}}

{{--                <p>{{ $field->name }}: {{ $asset->{$field->db_column_name()} }}</p>--}}

{{--            @endforeach--}}
{{--        @endif--}}


{{--        @if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))--}}
{{--            <div class="barcode_container">--}}
{{--                <img src="{{ $base64 }}" class="barcode">--}}
{{--            </div>--}}
{{--        @endif--}}




{{--    </div>--}}

{{--    @endif--}}
    <button class="print-btn">print</button>
@endforeach

