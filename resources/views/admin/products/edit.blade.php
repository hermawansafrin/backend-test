@extends('admin.__layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">{{ $subtitle ?? '' }}</h3>
                            <div class="card-toolbar float-right">
                                <a href="{{ route($ROUTE_PATH.'index', ) }}" class="btn btn-sm btn-danger">
                                    {{ __('button.back') }}
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            {{-- saat ada action benar atau salah --}}
                            @include('admin.__components.alert_session')
                            <form action="{{ route($ROUTE_PATH.'update', $params) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">
                                        {{ __($LANG_PATH.'form.name.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $editedData['name'] }}"
                                        placeholder="{{ __($LANG_PATH.'form.name.placeholder') }}" required>
                                    @error('name')
                                    <span class="text-danger ml-2" style="font-size:14px;">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">
                                        {{ __($LANG_PATH.'form.price.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="price" name="price" value="{{ $editedData['price'] }}"
                                        placeholder="{{ __($LANG_PATH.'form.price.placeholder') }}" required>
                                    @error('price')
                                    <span class="text-danger ml-2" style="font-size:14px;">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="stock" class="form-label">
                                        {{ __($LANG_PATH.'form.stock.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ $editedData['stock'] }}"
                                        placeholder="{{ __($LANG_PATH.'form.stock.placeholder') }}" required>
                                    @error('stock')
                                    <span class="text-danger ml-2" style="font-size:14px;">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="is_active" class="form-label">
                                        {{ __($LANG_PATH.'form.is_active.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="is_active" name="is_active" required>
                                        <option value="">{{ __($LANG_PATH.'form.is_active.placeholder') }}</option>
                                        <option value="1" {{ $editedData['is_active'] == 1 ? 'selected' : '' }}>{{ __('general.active') }}</option>
                                        <option value="0" {{ ($editedData['is_active'] == 0 && $editedData['is_active'] != null) ? 'selected' : '' }}>{{ __('general.inactive') }}</option>
                                    </select>
                                    @error('is_active')
                                        <span class="text-danger ml-2" style="font-size:14px;">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary m-2 col-12">
                                    {{ __('button.save') }}
                                </button>
                            </form>
                        </div>

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
