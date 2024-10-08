@extends('users::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('users.name') !!}</p>
    <table width="100%" class="table table-striped table-bordered">
        <caption> Tests All</caption>
        {{-- <a class="btn btn-success" href="exmaple2/create">Create</a>
            <a class="btn btn-secondary" href="exmaple2">WithOut Trashed</a>
            <a class="btn btn-danger" href="exmaple2?trashed=1">With Trashed</a> --}}

        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Photo</th>
                <th scope="col">Status</th>
                <th scope="col">Show Data</th>
                <th scope="col">Description</th>
                <th scope="col">Created At</th>
                <th scope="col">Updated At </th>
                <th scope="col">Deleted At</th>
                <!--  scope="col" <th>Deleted At</th>-->
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody width="100%">
            {{-- {{dd($tests)}} --}}
            @each('users::data', $data, 'data', 'users::empty_data')

        </tbody>
    </table>
@endsection
