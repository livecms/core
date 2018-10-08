<?php
$source = LC_CurrentTheme().'.views.layout';
$targetView = 'livecms-templates::'.$source; ?>
@extends($targetView)

@section('content')
<div class="row">
    <div class="col-sm-10">
        <h3 class="x_panel-title">Show</h3>
        <div class="x_panel-button">
            <a href="#" class="btn btn-default">
                <i class="fa fa-trash-o"></i>
            </a>
            <a href="#" class="btn btn-primary">
                <i class="fa fa-pencil"></i>
            </a>
        </div>
        <div class="x_panel">
            <div class="x_content table">
                <table class="table">
                    <thead>
                        <td width="35%">{{$keyFieldLabel}}</td>
                        <td width="65%">{{$keyFieldValue}}</td>
                    </thead>
                    <tbody>
                        @foreach($showFields as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{!!$value!!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection
