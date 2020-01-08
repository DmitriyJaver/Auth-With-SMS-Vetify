@extends('layouts.app')


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Settings') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('settings') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">

                                        <label class="form-check-label" for="useSmsVerify">
                                            <input class="form-check-input" type="checkbox" name="useSmsVerify"  {{ $user->use_sms_verify ? 'checked' : '' }}>
                                            {{ __('Use two factor verification. Add SMS verification') }} {{$user->name}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save Settings') }}
                                    </button>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
