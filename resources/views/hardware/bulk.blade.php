@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/hardware/form.update') }}
@parent
@stop


@section('header_right')
<a href="{{ URL::previous() }}" class="btn btn-sm btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">

    <p>{{ trans('admin/hardware/form.bulk_update_help') }}</p>

    <div class="callout callout-warning">
      <i class="fa fa-warning"></i> {{ trans('admin/hardware/form.bulk_update_warn', ['asset_count' => count($assets)]) }}
    </div>

    <form class="form-horizontal" method="post" action="{{ route('hardware/bulksave') }}" autocomplete="off" role="form">
      {{ csrf_field() }}

      <div class="box box-default">
        <div class="box-body">
          <!-- Purchase Date -->
          <div class="form-group {{ $errors->has('purchase_date') ? ' has-error' : '' }}">
            <label for="purchase_date" class="col-md-3 control-label">{{ trans('admin/hardware/form.date') }}</label>
            <div class="input-group col-md-3">
              <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
                <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}">
                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
              </div>
              {!! $errors->first('purchase_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
          </div>
          <!-- Expected Checkin Date -->
          <div class="form-group {{ $errors->has('expected_checkin') ? ' has-error' : '' }}">
             <label for="expected_checkin" class="col-md-3 control-label">{{ trans('admin/hardware/form.expected_checkin') }}</label>
             <div class="input-group col-md-3">
                  <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
                      <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="expected_checkin" id="expected_checkin" value="{{ old('expected_checkin') }}">
                      <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                 </div>
                 {!! $errors->first('expected_checkin', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
             </div>
          </div>


          <!-- Status -->
          <div class="form-group {{ $errors->has('status_id') ? ' has-error' : '' }}">
            <label for="status_id" class="col-md-3 control-label">
              {{ trans('admin/hardware/form.status') }}
            </label>
            <div class="col-md-7">
              {{ Form::select('status_id', $statuslabel_list , old('status_id'), array('class'=>'select2', 'style'=>'width:350px', 'aria-label'=>'status_id')) }}
              {!! $errors->first('status_id', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
          </div>

        @include ('partials.forms.edit.model-select', ['translated_name' => trans('admin/hardware/form.model'), 'fieldname' => 'model_id'])

          <!-- Default Location -->
        @include ('partials.forms.edit.location-select', ['translated_name' => trans('admin/hardware/form.default_location'), 'fieldname' => 'rtd_location_id'])

        <!-- Update actual location  -->
          <div class="form-group">
            <div class="col-md-3"></div>
            <div class="col-md-9">

                <label for="update_real_loc">
                  {{ Form::radio('update_real_loc', '1', old('update_real_loc'), ['class'=>'minimal', 'aria-label'=>'update_real_loc']) }}
                  Update default location AND actual location
                </label>
                <br>
                <label for="update_default_loc">
                  {{ Form::radio('update_real_loc', '0', old('update_real_loc'), ['class'=>'minimal', 'aria-label'=>'update_default_loc']) }}
                  Update only default location
                </label>

            </div>
          </div> <!--/form-group-->



          <!-- Purchase Cost -->
          <div class="form-group {{ $errors->has('purchase_cost') ? ' has-error' : '' }}">
            <label for="purchase_cost" class="col-md-3 control-label">
              {{ trans('admin/hardware/form.cost') }}
            </label>
            <div class="input-group col-md-3">
              <span class="input-group-addon">{{ $snipeSettings->default_currency }}</span>
                <input type="text" class="form-control"  maxlength="10" placeholder="{{ trans('admin/hardware/form.cost') }}" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost') }}">
                {!! $errors->first('purchase_cost', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
          </div>

          <!-- Supplier -->
           @include ('partials.forms.edit.supplier-select', ['translated_name' => trans('general.supplier'), 'fieldname' => 'supplier_id'])
          <!-- Company -->
          @include ('partials.forms.edit.company-select', ['translated_name' => trans('general.company'), 'fieldname' => 'company_id'])

          <!-- Order Number -->
          <div class="form-group {{ $errors->has('order_number') ? ' has-error' : '' }}">
            <label for="order_number" class="col-md-3 control-label">
              {{ trans('admin/hardware/form.order') }}
            </label>
            <div class="col-md-7">
              <input class="form-control" type="text" maxlength="20" name="order_number" id="order_number" value="{{ old('order_number') }}" />
              {!! $errors->first('order_number', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
          </div>

          <!-- Warranty -->
          <div class="form-group {{ $errors->has('warranty_months') ? ' has-error' : '' }}">
            <label for="warranty_months" class="col-md-3 control-label">
              {{ trans('admin/hardware/form.warranty') }}
            </label>
            <div class="col-md-3">
              <div class="input-group">
                <input class="col-md-3 form-control" maxlength="4" type="text" name="warranty_months" id="warranty_months" value="{{ old('warranty_months') }}" />
                <span class="input-group-addon">{{ trans('admin/hardware/form.months') }}</span>
                {!! $errors->first('warranty_months', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
              </div>
            </div>
          </div>

          <!-- Requestable -->
          <div class="form-group {{ $errors->has('requestable') ? ' has-error' : '' }}">
            <div class="control-label col-md-3">
              <strong>{{ trans('admin/hardware/form.requestable') }}</strong>
            </div>
            <div class="col-md-7">
              <label class="radio">
                <input type="radio" class="minimal" name="requestable" value="1"> Yes
              </label>
              <label class="radio">
                <input type="radio" class="minimal" name="requestable" value="0"> No
              </label>
              <label class="radio">
                <input type="radio" class="minimal" name="requestable" value=""> Do Not Change
              </label>
            </div>
          </div>

          @foreach ($assets as $key => $value)
            <input type="hidden" name="ids[{{ $key }}]" value="1">
          @endforeach
        </div> <!--/.box-body-->

        <div class="box-footer text-right">
          <button type="submit" class="btn btn-success"><i class="fa fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}</button>
        </div>
      </div> <!--/.box.box-default-->
    </form>
  </div> <!--/.col-md-8-->
</div>
@stop
