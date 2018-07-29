@extends('layout')

@section('content')
<div class='content col-md-12 col-xs-12'>
    <div class='col-xs-1'>
        <a href='/Sessions'><span class='glyphicon glyphicon-chevron-left'>Sessions</a>
    </div>

    <div class='eventViewTitle'>
        <h1>{{ $session->title }} </h1>
        <h4>Proctors: {{ $session->proctors }} </h4>
        <h4>Date: {{ $session->date }} </h4>
    </div>

	
    <div class='col-md-4 col-md-offset-0 col-xs-12'  style="margin-bottom:30px;">
        <h4>Check-In</h4>
        <form id="check-in_form" action="/Sessions/{{ $session->session_id }}-{{ $session->session_key }}" method="post" class="form-horizontal">
            <div class="form-group">
                <input autofocus autocomplete="off" type="user" name="student" class="form-control  col-xs-12" id="student" value='{{ $session->student_id ?: '' }}' placeholder="Enter net-id, email or student ID">
            </div>
            <div class="form-group ">
                <button id="submit" class="btn btn-success btn-outline ">Submit</button>
            </div>
            {{ csrf_field() }}
        </form>
    </div>



    <div class='col-md-4 col-xs-12 center-text'>
        
        <h4 class='col-xs-12'>Attendance</h4>
        <button id="randomStudent" class=" col-xs-12 btn btn-primary btn-sm"data-toggle="modal" data-target="#createModal">Random Student</button>
        <br/>
        <table id="eventsTable" class="scroll table col-md-12" >
            
            <tbody id='results'>
                <!--Populated Dynamically-->
                @if(count($attendance_list) > 0)
                    @foreach (array_reverse($attendance_list) as $student)
                        <tr class='attendee'>
                            <td class='name'>{{ $student['firstName'] }} {{ $student['lastName'] }} </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

    </div>

    <!-- Manual Event Status Updates -->
    <div class='col-md-4 col-xs-12'>
        <h4>Session Management</h4>
        <form action="/Sessions/{{ $session->session_id }}-{{ $session->session_key }}/process" method="post"class="form-horizontal">
        {{ csrf_field() }}
            <div class="form-group">
                <input id='eventID' type="hidden" name="eventID" value="{{ $session->session_id }}-{{ $session->session_key }}" >
                <div class="col-xs-12">
                    <button id="MAP" type="submit" class="btn btn-outline btn-danger">Process</button>
                </div>
            </div>
        </form>
        <div class="col-xs-12">
            <button id="edit" onclick="window.location='/Sessions/{{ $session->session_id }}-{{ $session->session_key }}/Edit';" class="btn btn-outline btn-danger">Edit</button>
        </div>

        
    </div>
	<!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Random Student</h4>
                </div>
                <div class="modal-body">
					<h4 id="randomName"><h4/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	<!-- End Modal -->
</div>
@endsection

@section('scripts')
    <!-- Page Specific Requirements -->
    <script type="text/javascript">
        $(document).ready(function(){
            console.log('fucking start');
            if($("#student").val() != ""){
                $("#submit").click();
            }

            Echo.private('class.{{ $session->id }}')
                .listen('AttendanceUpdated', (e) => {
                    console.log(e);
                    location.reload();
                });
			$("#randomStudent").click(function(){
				$("#randomName").text($("#eventsTable tr").eq(Math.floor(Math.random()*($("#eventsTable tr").length))).children('td').text());
			});
        });

</script>
@endsection