@extends('adminlte::page')

@section('title', $title)

@section('content_header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title ?? '' }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="users-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td>
                                            {{ $user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->roles->first()->name ?? '-' }}
                                        </td>
                                        <td>
                                            @if ($user->is_verified)
                                                <span style="color:green">Verified</span>
                                            @else
                                            <span style="color:red">Unverified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->is_verified)
                                                <a href="{{ route('user-edit-status', [$user->id, 0]) }}" type="button" class="btn btn-sm btn-outline-danger" >Unverified</a>
                                            @else
                                                <a href="{{ route('user-edit-status', [$user->id, 1]) }}" type="button" class="btn btn-sm btn-outline-success">Verified</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script>
        $(document).ready( function () {
            $('#users-table').DataTable();
        } );

        $(document).Toasts('create', {
            title: 'Toast Title',
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    </script>
@stop