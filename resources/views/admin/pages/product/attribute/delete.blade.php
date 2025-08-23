@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <form action="{{ route('user.destroy', ['id' => $user->id]) }}" method="post">
        @csrf
        @method('DELETE')
        <div class="mt-20">
            <div class="row">
                <div class="col-lg-5">
                    <div class="panel-body">
                        <h2>General Information</h2>
                        <p class="text-14 text-danger">- Note: You will not be able recover the data after it has been deleted</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row mb-15">
                                <div class="col-lg-6">
                                    <x-input :label="'Email'" :name="'email'" :value="$user->email ?? ''" :require="true" :readonly="true" />
                                </div>
                                <div class="col-lg-6">
                                    <x-input :label="'Fullname'" :name="'name'" :value="$user->name ?? ''" :require="true" :readonly="true" />
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="text-left mb-15">
                        <button type="submit" name="send" value="send" class="btn btn-danger text-13">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection