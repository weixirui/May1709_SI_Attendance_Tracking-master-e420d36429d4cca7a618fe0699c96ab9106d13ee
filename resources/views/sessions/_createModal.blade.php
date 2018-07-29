<!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="eventCreate" action="/Sessions" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create Session</h4>
                </div>
                <div>
                    
                        {{ csrf_field() }}
                        @if (strlen(Auth::user()->class) > 0)
                        <div class="col-md-12 form-group">
                            <label>Session Name: {{ Auth::user()->class }} {{ Auth::user()->name }} <span id="datePickerLabel"></span></label>
                            <br>
                        </div>
                        @else
                            <div class="col-md-12 form-group">
                            <label>Would you like to <a href="/Account">add your class</a> to your account to automate event naming? </li><br></label>
                            <input type="text" class="form-control" name='title' id="eventNameInput" placeholder="Enter session name">
                        </div>
                        @endif

                        <div class='dates col-md-12 col-xs-12'>
                            <div class="form-group col-md-4">
                                <div class="form-group input-group" readonly>
                                    <input type="text" class="form-control datetimepicker" name='startDate' id="date-fld1" readonly/>
                                    <label class="input-group-btn" for="date-fld1">
                                        <span class="btn btn-default">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <div class="input-group col-xs-12">
                                    <select name="sessionType" class="selectpickerphp form-control">
                                        <option value="Session">Session</option>
                                        <option value="Exam Review">Exam Review</option>
                                        <option value="Extended Session">Extended Session</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 form-group col-xs-12">
                                <div class="input-group col-xs-12">
                                    <select name="duration" class="selectpicker form-control">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="modal-footer">
                        <input type="submit" class="btn btn-default">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form> 
        </div>
    </div>
    <!-- End Modal -->