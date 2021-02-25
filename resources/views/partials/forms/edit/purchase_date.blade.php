<!-- Purchase Date -->
<div class="form-group {{ $errors->has('purchase_date') ? ' has-error' : '' }}">
   <label for="purchase_date" class="col-md-3 control-label">{{ trans('general.purchase_date') }}</label>
   <div class="input-group col-md-3">
        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
            <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', ($item->purchase_date) ? $item->purchase_date->format('Y-m-d') : '') }}">
            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
       </div>
       {!! $errors->first('purchase_date', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
   </div>
</div>

