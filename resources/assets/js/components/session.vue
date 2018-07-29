<template>

    <div class='session col-xs-12 col-sm-10 col-sm-offset-1 '>
        <div class='title col-md-5 col-xs-12'>
            {{ session.title }}
        </div>
        <div class='date col-md-3 col-sm-4 col-xs-12'>
            <span class='show-xs hidden-md hidden-lg'>Date: </span>
            <span class = 'YMD'>{{ session.date }}</span>
        </div>
        <div class='duration col-md-2 col-sm-3 col-xs-5'> <!-- To put duration and attendance on same row col-xs-# needs to be  6 on both so they can't both fit -->
            <span class='show-xs hidden-md hidden-lg'>Duration: </span>
            <span class=''><strong>{{ session.duration }}</strong></span>
        </div>
        <div class='attended col-md-2 col-sm-4 col-xs-7'>
            <span class='show-xs hidden-md hidden-lg'>Attendance: </span>
            <span class=''><strong>{{ session.attendance }}</strong></span>
        </div>
        <a v-bind:href=" 'Sessions/' + session.session_id + '-' + session.session_key "> 
            <span class="hyperspan"></span>
        </a>
    </div>

</template>

<script>
    export default {
        mounted() {
            console.log('Session mounted.')
        },
        props: ['session']
    }
</script>
