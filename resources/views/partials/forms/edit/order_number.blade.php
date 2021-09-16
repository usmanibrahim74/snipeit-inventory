<!-- Order Number -->
<div class="form-group {{ $errors->has('order_number') ? ' has-error' : '' }}">
   <label for="order_number" class="col-md-3 control-label">{{ trans('general.order_number') }}</label>
   <div class="col-md-7 col-sm-12 required">
       <input class="form-control " data-validation="required" required type="text" name="order_number" aria-label="order_number" id="order_number" value="{{ old('order_number', $item->order_number) }}" />
       {!! $errors->first('order_number', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
   </div>
</div>
