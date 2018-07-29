@extends('layout')



@section('content')

<div class='content'>

    <!-- Search and New session button -->
    <div class='col-xs-12 col-md-10 col-md-offset-1'>

        <div class='form-group form-group-horizontal col-md-9 col-xs-12'>
            <div class='input-group'>
                <input class='form-control' type='text' id="eventSearch" placeholder="Search Events by Name">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search" data-toggle="tooltip" data-placement="left" title="Keep the search name left"></i>
                </span>
            </div>
        </div>

        <div class='col-md-3 col-xs-12 '>
            <button type="button" class="btn btn-success btn-outline col-xs-12" id="createSessionBtn"data-toggle="modal" data-target="#createModal">New Session <span class='glyphicon glyphicon-plus'></button>
        </div>
        <div class="tooltip">Hover over me  <span class="tooltiptext">Tooltip text</span></div>

        <!-- This is where we include the modal for creating events -->
    @include('sessions._createModal')
    </div> 

        <!-- VUE SESSION TABLE is HERE-->
        <session_table v-bind:table_title='upcoming'></session_table>
        <session_table v-bind:table_title='completed'></session_table>
        <session_table v-bind:table_title='processed'></session_table>
</div>

@endsection
@section('scripts')
    <!-- Page Specific Requirements -->


    <!-- Datepicker -->
    <script src='/js/moment.min.js' type='text/javascript'></script>
    <script src='/js/bootstrap-datetimepicker.min.js' type='text/javascript'></script>
    
    <script type='text/javascript'>
        // tablesorter and datetimepicker conflict, this fixes that
        $.noConflict();
        jQuery(document).ready(function($){
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                ignoreReadonly: true
            });

            $('[data-toggle="tooltip"]').tooltip();

			$('#eventSearch').keyup(function(){
				var filter = $("#eventSearch").val().toUpperCase();
				var session    = $('.session').not('.headers');
				var title     = $('.session').not('.headers').find('.title');
				var date      = $('.session').not('.headers').find('.YMD');
				var duration  = $('.session').not('.headers').find('.duration').find('strong');
				var attended = $('.session').not('.headers').find('.attended').find('strong');
				
				for(i = 0; i < session.length; i++){
					if((title[i].innerHTML.toUpperCase().indexOf(filter) > -1) ||
					(((filter.indexOf('-') > -1)||(filter.length >= 4))&&(date[i].innerHTML.toUpperCase().indexOf(filter) > -1))||
					((filter.length == 2)&&((date[i].innerHTML.substring(5,7) == filter)||(date[i].innerHTML.substring(8,10) == filter)))||
					(duration[i].innerHTML == filter)||
					(attended[i].innerHTML == filter)){
						session[i].style.display = "";
					}else{
						session[i].style.display = "none";
					}
				}
			});
        });
    

    </script>

@endsection

