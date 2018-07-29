@extends('layout')

@section('content')

<div class='content'>
    <h1>Edit Account</h1>
    <div class="col-md-6 col-md-offset-3">

        <form id="editAccount" action="/Account" method="POST">
            {{ csrf_field() }}
            <div class="col-md-12 form-group">
                <label>Class<br></label>
              <input type="text" class="form-control" name='class' id="classInput" placeholder="Enter Class Name: (eg: Math 166)">
            </div>
            <input type="submit" class="btn">
        </form>
    </div>
</div>

@endsection
