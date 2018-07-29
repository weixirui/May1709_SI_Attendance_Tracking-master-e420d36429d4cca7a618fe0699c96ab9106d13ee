@extends('layout')

@section('scripts')
    <!-- Page Specific Requirements -->
    <script src='/js/moment.min.js' type='text/javascript'></script>
    <script src='/js/bootstrap-datetimepicker.min.js' type='text/javascript'></script>
    <script type='text/javascript'>
        $(document).ready(function(){
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });
    </script>

    <link rel='stylesheet' href='/css/bootstrap-datetimepicker.min.css'>
@endsection

@section('content')
<div class='content'>
    <h1>Create Session</h1>
    <div class="col-md-6 col-md-offset-3">

        <form id="eventCreate" action="/Sessions" method="POST">
            {{ csrf_field() }}
            <div class="col-md-12 form-group">
                <label>Session Name<br></label>
        		<input type="text" class="form-control" name='title' id="eventNameInput" placeholder="Enter session name">
          	</div>
            <div class='dates col-md-12 col-xs-12'>
                <div class="form-group col-md-6">
                    <div class="input-group date">
                		<input type='text' name='startDate' class="datetimepicker form-control" id="startDateInput" placeholder='Session Date'>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                  	</div>
                </div>
                <div class="col-md-6 form-group ">
                    <div class="input-group col-xs-12">
                		<input type='number' name='duration' class="form-control" value='1' placeholder='Session Duration'>
                  	</div>
                </div>
            </div>


            <input type="submit" class="btn" onclick="queren()">
        </form>
    </div>
</div>

<script>
function queren()
{
  alert("这个窗口是对话框！")

}
    // no one knows why, but these script tags are required to work
</script>

@endsection
