@section('javascript')
<script>
    $(function () {

        $('#province').change(function() {
            var id = $("#province option:selected").val();
            $('#city').select2({
                placeholder: 'Select an District',
                ajax: {
                    url: '{{ route("city-api") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                        q: params.term, // search term
                        p: id,
                    };
                },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }   
            });
        });

        $('#city').change(function() {
            var id = $("#city option:selected").val();
            $('#district').select2({
                placeholder: 'Select an District',
                ajax: {
                    url: '{{ route("district-api") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                        q: params.term, // search term
                        p: id,
                    };
                },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }   
            });
        });

    }); //end function
</script>
@endsection

<div class="form-group">
    <label class="col-md-2 control-label">
        {{ Helper::label('name') }}
    </label>
    <div class="col-md-4 {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    <label class="col-md-2 control-label">
        {{ Helper::label('contact') }}
    </label>
    <div class="col-md-4 {{ $errors->has('contact') ? 'has-error' : ''}}">
        {!! Form::text('contact', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">
        {{ Helper::label('phone') }}
    </label>
    <div class="col-md-4 {{ $errors->has('phone') ? 'has-error' : ''}}">
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    </div>
    <label class="col-md-2 control-label">
        {{ Helper::label('email') }}
    </label>
    <div class="col-md-4 {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div>
<hr/>
<div class="form-group">
    <label class="col-md-2 control-label">
        {{ Helper::label('email') }}
    </label>
    <div class="col-md-4 {{ $errors->has('email') ? 'has-error' : ''}}">
        {{ Form::select('email', $user->pluck('name', 'email'), null, ['placeholder' => 'Please Select User', 'class' => 'form-control' , 'id' => 'user', 'data-plugin-selectTwo']) }}
    </div>
    <label class="col-md-2 control-label">
        {{ Helper::label('status') }}
    </label>
    <div class="col-md-4 {{ $errors->has('status') ? 'has-error' : ''}}">
        {{ Form::select('status', $status, null, ['class' => 'form-control', 'data-plugin-selectTwo']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">
         {{ Helper::label('site_id') }}
    </label>
    <div class="col-md-4 {{ $errors->has('site_id') ? 'has-error' : ''}}">
        {{ Form::select('site_id', $site->pluck('site_name', 'site_id'), null, ['placeholder' => 'Please Select Site', 'class' => 'form-control' , 'id' => 'site', 'data-plugin-selectTwo']) }}
    </div>
    <label class="col-md-2 control-label">
        {{ Helper::label('province_id') }}
    </label>
    <div class="col-md-4 {{ $errors->has('province_id') ? 'has-error' : ''}}">
        {{ Form::select('province_id', $province->pluck('province', 'province_id'), null, ['placeholder' => 'Please Select Province', 'class' => 'form-control', 'id' => 'province', 'data-plugin-selectTwo']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">
        {{ Helper::label('city_id') }}
    </label>
    <div class="col-md-4 {{ $errors->has('city_id') ? 'has-error' : ''}}">
        {{ Form::select('city_id', $city, null, ['placeholder' => 'Please Select City', 'class' => 'form-control', 'id' => 'city', 'data-plugin-selectTwo']) }}
    </div>
    <label class="col-md-2 control-label">
        {{ Helper::label('subdistrict_id') }}
    </label>
    <div class="col-md-4 {{ $errors->has('subdistrict_id') ? 'has-error' : ''}}">
        {{ Form::select('subdistrict_id', $district,null, ['placeholder' => 'Please Select District', 'class' => 'form-control', 'id' => 'district', 'data-plugin-selectTwo']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">
        {{ Helper::label('address') }}
    </label>
    <div class="col-md-10 {{ $errors->has('address') ? 'has-error' : ''}}">
        {!! Form::textarea('address', null, ['class' => 'form-control','rows' => '3']) !!}
    </div>
</div>