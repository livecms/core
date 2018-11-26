<?php
$source = LC_CurrentTheme().'.views.layout';
$targetView = 'livecms-templates::'.$source; ?>
@extends($targetView)

@section('content')
<h3 class="x_panel-title">{{str_plural(ResTitle())}}</h3>
<div class="x_panel-button">
    <a href="{{ResRoute('create')}}" class="btn btn-primary">
        Create {{ResTitle()}}
    </a>
</div>
<div class="x_panel">
    <div class="x_content">
        <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
            <thead>
                <tr>
                @foreach ($dataTablesCaptions as $field)
                    <th @if (strtolower($field) == 'action') class="text-right" @endif>
                        {{ $field }}
                    </th>
                @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js-bottom')
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatables').DataTable({!! $dataTablesView !!});
    });
    @if (config('app.debug') == false)
    $.fn.dataTable.ext.errMode = 'none';
    @endif
</script>
@endpush
