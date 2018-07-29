@extends('layout')

@section('scripts')
    <!-- Datepicker -->
    <script src='/js/moment.min.js' type='text/javascript'></script>
    <script src='/js/bootstrap-datetimepicker.min.js' type='text/javascript'></script>
    <script type='text/javascript'>
        // tablesorter and datetimepicker conflict, this fixes that
        $(document).ready(function(){
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                ignoreReadonly: true
            });
        });
    </script>

    <link rel='stylesheet' href='/css/bootstrap-datetimepicker.min.css'>


    
    <!-- Script for all the features of this page -->
    <script type="text/javascript" src="js/index.js"></script> 
@endsection





@section('content')
<div class='content col-md-12 col-xs-12'>
    <div class='col-xs-1'>
        <a href='/Sessions'><span class='glyphicon glyphicon-chevron-left'>Sessions</a>
    </div>

    <div class='eventViewTitle'>
        <h1> Edit Event </h1>
    </div>


    <div class='col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1'>
        <form action="/Sessions/{{ $session->session_id }}-{{ $session->session_key }}/Update" method="post" class="form-horizontal">
            {{ csrf_field() }}
            <div class="col-md-12 form-group">
                <input type="text" class="form-control" name='title' id="eventNameInput" value= "{{ $session->title }}" required>
            </div>
            <div class='dates col-md-12 col-xs-12'>
                <div class="form-group input-group col-xs-12" readonly>
                    <input type="text" class="form-control datetimepicker" name='startDate' id="startDateInput" value="{{ $session->date }}" readonly required/>
                    <label class="input-group-btn" for="startDateInput">
                        <span class="btn btn-default">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </label>
                </div>
                <div class="col-md-12 form-group col-xs-12">
                    <div class="input-group col-xs-12">
                        <select name="duration" class="form-control">
                            @for ($i = 1; $i < 5; $i++)
                                <option value="{{ $i }}" {{ $i == $session->duration ? 'selected="selected"' : '' }}>{{ $i }}</option>  
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-success btn-outline">Update</button>
            </div>
        </form>
			<div class="form-group">
                <button id="delete" type="cancel" class="btn btn-danger btn-outline" data-toggle="modal" data-target="#createModal">Delete</button>
			</div>
        <h4>Students Present</h4>
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
	<!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Are you sure you want to Delete this event?</h4>
                </div>
                <div class="modal-body">
					<form action="/Sessions/{{ $session->session_id }}-{{ $session->session_key }}/Delete" method="post" class="form-horizontal">
						{{ csrf_field() }}
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-outline">Yes</button>
							<button type="button" type="cancel "class="btn btn-primary btn-outline" data-dismiss="modal">No</button>
						</div>
					</form>
                    
                </div>
            </div>
        </div>
    </div>
	<!-- End Modal -->
</div>

@endsection


