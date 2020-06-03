@extends('layouts.theme2')
@section('isi')

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Import Data dari table dikti</h3>
    </div>
    <div class="box-body">

        <form method="POST" class="loading" action="{{route('import.dikti')}}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="{{ $errors->has('file') ? ' has-error' : '' }}">
                <label for="file" class="col-md-1 control-label">File</label>
                <div class="col-md-7">
                    <input id="file" class="" type="file" name="file" accept=".xls,.xlsx,.cvs" required>
                    @if ($errors->has('file'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file') }}</strong>
                    </span>
                    @endif
                </div>

            </div>
            <div class="col-md-4">
                <button type="submit" id="tombol-import" class="btn btn-primary btn-block">
                    IMPORT DATA
                </button>
            </div>

        </form>
    </div>
    <!-- /.box-body -->
</div>

@endsection
