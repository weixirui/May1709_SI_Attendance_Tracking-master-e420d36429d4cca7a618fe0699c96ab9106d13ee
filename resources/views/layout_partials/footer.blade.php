<script src="{{ asset('js/app.js') }}"></script>

@if (!Auth::guest())
    <script>
        // listen for user notifications *Note this script only exists if the client is a logged in user
        Echo.private('App.User.' + {{ Auth::User()->id }})
            .notification((notification) => {
                
                // Handle success messages
                if (notification.notificationType == 'success') {
                    // Generate success toast (with click link to refresh home page)
                    Toastr.success(notification.message, notification.session_title, {onclick: function() { window.location.assign('/Sessions') }});

                    // Special Actions if this is a session processed notification
                    if (notification.type.includes('SessionProcessed')) {
                        // use blade to inject user data into a js string to get the user's Box file name
                        var filename = '- {{ Auth::User()->class }} ({{ explode('@', Auth::User()->email)[0] }}).xlsx'

                        // Generate info toast that opens box in a new tab.
                        Toastr.info( filename, 'View this change in box: ', {onclick: function() {window.open('https://iastate.app.box.com/folder/22073241963')}});
                    }
                }

                // Handle failure notifications 
                else if (notification.notificationType == 'fail') {
                    // Generate error toast
                    Toastr.error(notification.message, notification.session_title);
                } 

                // Handle warning notifications
                else if (notification.notificationType == 'warning') {
                    // Generate warning toast
                    Toastr.warning(notification.message, notification.session_title);
                }

                // Handle info notifications
                else if (notification.notificationType == 'info') {
                    // Generate info toast
                    Toastr.info(notification.message, notification.session_title);
                }
            });
    </script>
    <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
@endif

@yield('scripts')
