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
                                <a href="{{ route($ROUTE_PATH.'index') }}" class="btn btn-sm btn-danger">
                                    {{ __('button.back') }}
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            {{-- saat ada action benar atau salah --}}
                            @include('admin.__components.alert_session')
                            <form action="{{ route($ROUTE_PATH.'store') }}" method="POST">
                                @csrf
                                @method('post')

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">
                                        {{ __($LANG_PATH.'form.name.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                        placeholder="{{ __($LANG_PATH.'form.name.placeholder') }}" required>
                                    @error('name')
                                    <span class="text-danger ml-2" style="font-size:14px;">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">
                                        {{ __($LANG_PATH.'form.email.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="{{ __($LANG_PATH.'form.email.placeholder') }}" required>
                                    @error('email')
                                    <span class="text-danger ml-2" style="font-size:14px;">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">
                                        {{ __($LANG_PATH.'form.phone.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="{{ __($LANG_PATH.'form.phone.placeholder') }}" required>
                                    @error('phone')
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
