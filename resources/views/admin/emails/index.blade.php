@extends('layouts.admin')
@section('content')
    @can('email_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.emails.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.email.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Email', 'route' => 'admin.emails.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
           @if($list!='') {{$list->list_title}}'s {{ trans('cruds.email.title_singular') }}s @else {{ trans('cruds.email.title_singular') }} {{ trans('global.list') }}@endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Email">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.email.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.email.fields.email_contact') }}
                        </th>
                        <th>
                            {{ trans('cruds.email.fields.phone') }}
                        </th>
                        <th>
                            {{ trans('cruds.email.fields.company_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.email.fields.job_level') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($companies as $key => $item)
                                    <option value="{{ $item->company_name }}">{{ $item->company_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($job_positions as $key => $item)
                                    <option value="{{ $item->job_title }}">{{ $item->job_title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($emails as $key => $email)
                        <tr data-entry-id="{{ $email->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $email->id ?? '' }}
                            </td>
                            <td>
                                {{ $email->email_contact ?? '' }}
                            </td>
                            <td>
                                {{ $email->phone ?? '' }}
                            </td>
                            <td>
                                {{ $email->company_name->company_name ?? '' }}
                            </td>
                            <td>
                                {{ $email->job_level->job_title ?? '' }}
                            </td>
                            <td>
                                @can('email_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.emails.show', $email->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('email_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.emails.edit', $email->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('email_delete')
                                    <form action="{{ route('admin.emails.destroy', $email->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('email_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.emails.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }})
                            .done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[ 1, 'desc' ]],
                pageLength: 50,
            });
            let table = $('.datatable-Email:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function () {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        })

    </script>
@endsection
