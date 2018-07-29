@extends('layout')

@section('content')
<div class='content'>
    <!-- We need to use HTTPS for this. Currently passwords are very insecure... but they also aren't really implemented anyway -->
    <form action="login" method="post"class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-sm-4" for="email">Net ID:</label>
        <div class="col-sm-4">
          <input type="username" name="username" class="form-control" id="username" placeholder="Enter your Iowa State Net-ID">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4" for="pwd">Password:</label>
        <div class="col-sm-4">
          <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter password">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4" for="pwd">Re-Password:</label>
        <div class="col-sm-4">
          <input type="password" name="password2" class="form-control" id="re_pwd" placeholder="Enter password again">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-4">
          <button type="submit" class="btn btn-success btn-outline">Submit</button>
        </div>
      </div>
      </div>
    </form>
</div>
@endsection
