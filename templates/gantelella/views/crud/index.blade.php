<?php
$source = LC_CurrentTheme().'.views.layout';
$targetView = 'livecms-templates::'.$source; ?>
@extends($targetView)

@section('content')
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
@endsection

@push('js-bottom')
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatables').DataTable({!! $dataTablesView !!});
    });
</script>
@endpush
