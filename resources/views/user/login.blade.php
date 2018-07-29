@extends('layout')

@section('content')
    <div class='content'>
        <a href="CreateUser"> Create an Account </a>

        <form action="Tasks/login.php" method="post"class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-4" for="username">Net-ID:</label>
                <div class="col-sm-4">
                    <input type="username" class="form-control" name="username" id="username" placeholder="Enter Iowa State Net-ID">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="pwd">Password:</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
