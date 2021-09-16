@extends('layouts/default')

@section('title0')

  @if ((Request::get('company_id')) && ($company))
    {{ $company->name }}
  @endif



@if (Request::get('status'))
  @if (Request::get('status')=='Pending')
    {{ trans('general.pending') }}
  @elseif (Request::get('status')=='RTD')
    {{ trans('general.ready_to_deploy') }}
  @elseif (Request::get('status')=='Deployed')
    {{ trans('general.deployed') }}
  @elseif (Request::get('status')=='Undeployable')
    {{ trans('general.undeployable') }}
  @elseif (Request::get('status')=='Deployable')
    {{ trans('general.deployed') }}
  @elseif (Request::get('status')=='Requestable')
    {{ trans('admin/hardware/general.requestable') }}
  @elseif (Request::get('status')=='Archived')
    {{ trans('general.archived') }}
  @elseif (Request::get('status')=='Deleted')
    {{ trans('general.deleted') }}
  @endif
@else
{{ trans('general.all') }}
@endif
{{ trans('general.assets') }}

  @if (Request::has('order_number'))
    : Order #{{ Request::get('order_number') }}
  @endif
@stop

{{-- Page title --}}
@section('title')
@yield('title0')  @parent
@stop

@section('header_right')
  <a href="{{ route('reports/custom') }}" style="margin-right: 5px;" class="btn btn-default">
    Custom Export</a>
  @can('create', \App\Models\Asset::class)
  <a href="{{ route('hardware.create') }}" class="btn btn-primary pull-right"></i> {{ trans('general.create') }}</a>
  @endcan

@stop

{{-- Page content --}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-body">
        {{ Form::open([
          'method' => 'POST',
          'route' => ['hardware/bulkedit'],
          'class' => 'form-inline',
           'id' => 'bulkForm']) }}
          <div class="row">
            <div class="col-md-12">
              @if (Request::get('status')!='Deleted')
              <div id="toolbar">
                <label for="bulk_actions"><span class="sr-only">Bulk Actions</span></label>
                <select name="bulk_actions" class="form-control select2" aria-label="bulk_actions">
                  <option value="edit">{{ trans('button.edit') }}</option>
                  <option value="delete">{{ trans('button.delete') }}</option>
                  <option value="labels">{{ trans_choice('button.generate_labels', 2) }}</option>
                </select>
                <button class="btn btn-primary" id="bulkEdit" disabled>Go</button>
              </div>
              @endif

              <table
                data-advanced-search="true"
                data-click-to-select="true"
                data-columns="{{ \App\Presenters\AssetPresenter::dataTableLayout() }}"
                data-cookie-id-table="assetsListingTable"
                data-pagination="true"
                data-id-table="assetsListingTable"
                data-search="true"
                data-side-pagination="server"
                data-show-columns="true"
                data-show-export="true"
                data-show-footer="true"
                data-show-refresh="true"
                data-sort-order="asc"
                data-sort-name="name"
                data-toolbar="#toolbar"
                id="assetsListingTable"
                class="table table-striped snipe-table"
                data-url="{{ route('api.assets.index',
                    array('status' => e(Request::get('status')),
                    'order_number'=>e(Request::get('order_number')),
                    'company_id'=>e(Request::get('company_id')),
                    'status_id'=>e(Request::get('status_id')))) }}"
                data-export-options='{
                "fileName": "export{{ (Request::has('status')) ? '-'.str_slug(Request::get('status')) : '' }}-assets-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
              </table>

            </div><!-- /.col -->
          </div><!-- /.row -->
        {{ Form::close() }}

          <script>
              // var myForm = document.getElementById('bulkForm');
              // myForm.onsubmit = function(e) {
              //     var select = document.querySelector('select[name=bulk_actions]').value;
              //     if(select == "labels"){
              //         var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300,left = 312,top = 234');
              //         this.target = 'Popup_Window';
              //     }
              // };
          </script>
      </div><!-- ./box-body -->
    </div><!-- /.box -->
  </div>

  <div class="labels-container d-none">

  </div>
</div>

@stop

@section('moar_scripts')
@include('partials.bootstrap-table')

<script src="{{ asset('/js/jspdf.min.js') }}"></script>
<script>
  function convertImageToCanvas(image) {
    var canvas = document.createElement("canvas");
    canvas.width = image.width;
    canvas.height = image.height;
    canvas.getContext("2d").drawImage(image, 0, 0);

    return canvas;
  }
  function convertCanvasToImage(canvas,callback) {
    var image = new Image();
    image.src = canvas.toDataURL("image/jpeg");
    return image;
  }

  $(document).ready(()=>{
    $('#bulkForm').submit((e)=>{

      let action = $('[name="bulk_actions"]').val();
      if(action=="labels"){
        e.preventDefault();
        let url = $('#bulkForm').attr('action');
        let data = $('#bulkForm').serialize()
        $.post(url,data,(response)=>{
          $('.labels-container').html(response);
          generatePdf();
          $('#bulkForm input[type=hidden]:not([name=_token])').remove()
        });
      }
    })
  })

  let text_count=0;
  async function generateLabel(i,labels,doc){
    var item = labels[i];



    var image = $(item).find('img')[0];
    if(image){
      var img = new Image();
      img.src = image.src;
      img.onload = ()=>{
        var canvas = convertImageToCanvas(img);
        var imgData = convertCanvasToImage(canvas);

        console.log(imgData);
        doc.addImage(imgData, 'JPEG', 1.2, 0.1, 0.20, 0.2);
        text_count=0;
        setTextData(item,doc);
        if(i < labels.length - 1){
          doc.addPage()
          i = i+1;
          generateLabel(i,labels,doc);
        }else{
          doc.save("a4.pdf");
        }

      }

    }else{
      text_count=0;
      setTextData(item,doc);
      if(i < labels.length - 1){
        doc.addPage()
        i = i+1;
        generateLabel(i,labels,doc);
      }else{
        doc.save("a4.pdf");
      }
    }






  }
  function setTextData(item,doc){
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
      if(text.toLowerCase().includes('sid') && !isCustomLabel){
        doc.setFontSize({{ $settings->labels_fontsize*2.5 }});
        lineY +=0.09;
      }else if(text.toLowerCase().includes('model') && !isCustomLabel ){
        text = text.replace("Model: ","");
        doc.setFontSize({{ $settings->labels_fontsize*1.5 }});
        lineY +=0.04;

      }else if(text.toLowerCase().includes('category')){
        doc.setFontSize({{ $settings->labels_fontsize*1.2 }});
        // lineY -=0.0;
      }else{
        doc.setFontSize({{ $settings->labels_fontsize*1.2 }});
      }
      if(text_count == 5){
        doc.addPage();
        lineY = 0.1;
        text_count = 0;
      }else{
        text_count++;
      }
      doc.text(lineX, lineY, text);

      lineY += increment;

    })
  }

  function generatePdf(){
    var doc = new jsPDF({
      orientation: "landscape",
      unit: "in",
      format: [{{ $settings->labels_width }}, {{ $settings->labels_height }}]
    })



    var labels = $('.label');
    if(labels.length){
      generateLabel(0,labels,doc)
    }



  }
</script>
@stop
