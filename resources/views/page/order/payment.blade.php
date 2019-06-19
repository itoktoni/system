@extends('backend.'.config('website.backend').'.layouts.app') @section('content')
<div class="row">
    @isset($data)
    {!! Form::model($data, ['route'=>[$form.'_payment', $data->$key],'class'=>'form-horizontal','files'=>true]) !!}
    @else
    {!! Form::open(['route' => $form.'_payment', 'class' => 'form-horizontal', 'files' => true]) !!} 
    @endisset
    <div class="panel panel-default" style="margin-bottom: 300px;">
        <header class="panel-heading">
            <h2 class="panel-title">Konfirmasi Pembayaran</h2>
        </header>
        <div class="panel-body">
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    <label class="col-md-2 control-label">No order</label>
                    <div class="col-md-4 {{ $errors->has('reference') ? 'has-error' : ''}}">
                        <select class="form-control option" name="reference">
                            <option selected=" " value="{{ isset($data->order_id) ? $data->order_id : '' }}">
                                {{ isset($data->order_id) ? $data->order_id : 'Silahkan Pilih order' }}
                            </option>
                            @isset($list_order) 
                            @foreach($list_order as $value)
                            <option @isset($data) {{ $value->order_id == $data->order_id ? 'selected="selected"' : '' }} @endisset value="{{ $value->order_id }}"> {{ $value->order_id }}
                            </option>
                            @endforeach 
                            @endisset
                        </select>
                    </div>
                    <label class="col-md-2 control-label">Tanggal Transfer</label>
                    <div class="col-md-4 {{ $errors->has('payment_date') ? 'has-error' : ''}}">
                        <div class="input-group">
                            {!! Form::text('payment_date', null, ['class' => 'form-control datepicker']) !!}
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Nama Pemilik Rekening</label>
                    <div class="col-md-4 {{ $errors->has('payment_person') ? 'has-error' : ''}}">
                        {!! Form::text('payment_person', null, ['class' => 'form-control col-md-4']) !!}
                    </div>
                    <label class="col-md-2 control-label">Nama Bank Pemilik</label>
                    <div class="col-md-4 {{ $errors->has('account_From') ? 'has-error' : ''}}">
                        {!! Form::text('account_from', null, ['class' => 'form-control col-md-4']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Rekening Tujuan</label>
                    <div class="col-md-4 {{ $errors->has('account_to') ? 'has-error' : ''}}">
                        <select class="form-control col-md-4" id="account_id" name="account_to">
                            @foreach($account as $value)
                            <option @isset($data) {{ $value->account_name == $data->account_to ? 'selected="selected"' : '' }} @endisset value="{{ $value->account_name }}">{{ $value->account_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-md-2 control-label">Nilai Transfer</label>
                    <div class="col-md-4 {{ $errors->has('payment_amount') ? 'has-error' : ''}}">
                        {!! Form::number('payment_amount', null, ['class' => 'form-control col-md-4']) !!}
                    </div>
                    
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="textareaDefault">Upload Bukti Transfer</label>
                    <div class="col-md-4">
                        {!! Form::file('files', ['class' => 'btn btn-default btn-sm btn-block']) !!}
                        <h2 class="text-right">
                            @isset($tagihan)
                              Sisa Hutang : {{ number_format(($tagihan + $data->estimasi_cost)-$pembayaran) }} 
                            @endisset
                        </h2>
                    </div>
                    <label class="col-md-2 control-label">Catatan Tambahan</label>
                    <div class="col-md-4">
                        {!! Form::textarea('payment_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
                @isset($key)
                <hr>

                <div class="form-group">
                    <table class="table table-no-more table-bordered table-striped mb-none">
                        <thead>
                            <tr>
                                <th width="50" class="text-left">ID</th>
                                <th width="100" class="text-left">From</th>
                                <th width="100" class="text-left">TO</th>
                                <th  width="120" class="text-left">Nama Pemilik</th>
                                <th width="100" class="text-right">Nilai Transfer</th>
                                <th width="100" class="text-right">Attachment</th>
                                <th class="text-left">Note</th>
                                <th class="text-left">Tgl Bayar</th>
                                <th  width="80" class="text-center">Status</th>
                                <th  width="120" class="text-left">Approved By</th>
                                <th  width="80" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($detail) > 0)
                            @php
                            $total = 0;
                            @endphp                        
                            @foreach ($detail as $value)
                            @php
                            $amount = $value->payment_amount;
                            $total = $total + $amount;
                            @endphp
                            <tr>
                                <td data-title="ID">{{ $value->payment_id }}</td>
                                <td data-title="From">{{ $value->account_from }}</td>
                                <td data-title="TO">{{ $value->account_to }}</td>
                                <td data-title="Name">{{ $value->payment_person }}</td>
                                <td data-title="Amount" align="right">{{ number_format($value->payment_amount) }}</td>
                                <td data-title="Attachment" align="center">
                                    <a target="_blank" class="btn btn-primary btn-xs" href="{{asset('public/files/payment/'.$value->payment_attachment)}}">{{ $value->payment_attachment }}</a>
                                </td>
                                <td data-title="Note">{{ $value->payment_description }}</td>
                                <td data-title="Note">{{ $value->payment_date }}</td>
                                <td data-title="Status">{{ $value->payment_status }}</td>
                                <td align="center" data-title="Approve">{{ isset($value->approved_by) ? $value->approved_by : '-' }}</td>
                                <td align="center" data-title="Action"><a class="btn btn-danger btn-xs" href="{{ route($form.'_payment', ['delete' => $value->payment_id]) }}">delete</a></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="10" class="text-right">
                                    <strong class="text-center text-info"> 
                                        Total Pesanan
                                    </strong>
                                </td>
                                <td colspan="1" class="text-right">
                                    <strong class="text-center text-info"> 
                                     {{ number_format($tagihan) }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="text-right">
                                    <strong class="text-center text-warning"> 
                                        Biaya Pengiriman
                                    </strong>
                                </td>
                                <td colspan="1" class="text-right">
                                    <strong class="text-center text-warning"> 
                                     {{ number_format($data->estimasi_cost) }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="text-right">
                                    <strong class="text-center text-danger"> 
                                        Total Tagihan
                                        <input type="hidden" value="{{$tagihan}}" name="total_tagihan">
                                    </strong>
                                </td>
                                <td colspan="1" class="text-right">
                                    <strong class="text-center text-danger"> 
                                     {{ number_format($tagihan+$data->estimasi_cost) }}
                                    </strong>
                                </td>
                            </tr>
                             
                            <tr>
                                <td colspan="10" class="text-right">
                                    <strong class="text-center text-success"> 
                                        Total Pembayaran
                                    </strong>
                                </td>
                                <td colspan="1" class="text-right">
                                    <strong class="text-center text-success"> 
                                     {{ number_format($pembayaran) }}
                                    </strong>
                                </td>
                            </tr>
                             @else
                             <tr>
                                <td colspan="11" class="text-center">
                                    <strong class="text-center text-danger"> 
                                        Data Pembayaran {{ $data->order_id }} masih Kosong ! 
                                    </strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="10" class="text-right">
                                    <strong class="text-center text-primary"> 
                                        Sisa Tagihan
                                        <input type="hidden" value="{{ $tagihan }}" name="sisa_tagihan">
                                    </strong>
                                </td>
                                <td colspan="1" class="text-right">
                                    <strong class="text-center text-primary"> 
                                     {{ number_format(($tagihan+$data->estimasi_cost)-$pembayaran) }}
                                    </strong>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
            @endisset
        </div>
    </div>

    <div class="navbar-fixed-bottom" id="menu_action">
        <div class="text-right" style="padding:5px">
            <a href="{!! route("{$form}_list") !!}" class="btn btn-warning">Back</a>
            <button type="reset" class="btn btn-default">Reset</button>
            @isset($update)
            <button type="submit" class="btn btn-primary">Save</button>
            @endisset @isset($payment)
            <button type="submit" class="btn btn-primary">Save</button>
            @endisset
        </div>
    </div>
</div>
</div>
{!! Form::close() !!} @endsection