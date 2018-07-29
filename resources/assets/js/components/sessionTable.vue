<template>
    <div class='session_table col-xs-12'>
        <button v-on:click="toggle = !toggle" type='button' class='btn btn-outline upcomingEventsHeader col-xs-3 col-sm-offset-1'>
            <h2 class='col-xs-12'><i v-if="toggle == false" class='glyphicon glyphicon-collapse-down small'></i><i v-if="toggle == true" class='glyphicon glyphicon-collapse-up small'></i>{{ table_title }}</h2>
        </button>

        <!-- Table headers [only visible on md or larger] -->
        <div class="s_table">
            <div class='session headers col-xs-12 col-sm-10 col-sm-offset-1 '>
                    <div class='title col-md-5 col-xs-12 hidden-xs hidden-sm'> Session Name </div>
                    <div class='date col-md-3 col-sm-4 col-xs-12 hidden-xs hidden-sm'> Date </div>
                    <div class='duration col-md-2 col-sm-3 col-xs-6 hidden-xs hidden-sm'> Duration </div>
                    <div class='attended col-md-2 col-sm-4 col-xs-6 hidden-xs hidden-sm'> Attended </div>
            </div>

            <session v-if="toggle == false" v-for="session in sessions" v-bind:session="session"></session>
            <!-- Needs to be updated, puts label in wrong spot-->
            <div class='no-data col-xs-12' v-if="sessions.length == 0">
                There are no sessions to display for this table.
            </div>
        </div>
    
    </div>

</template>

<script>
    export default {
        mounted() {
            console.log('Session Table mounted.')
        },
        props: [
            'table_title',
            'session_id',
            'session_key',
            // 'another_prop'
        ],
        data: function() {
        return {
            sessions: [],
            toggle: false
        }
        },
        created() {

            // call our getSessions function everytime the page is created
            this.getSessions()
            
        },
        methods: {
            getSessions() {
                // get all sesions based on the appropriate session status
                axios.get('Sessions/load/' + this.table_title).then((response) => {
                    this.sessions = response.data;
                })
            },
            show() {
                var router = new VueRouter();
                router.go('/Sessions/' + this.session_id + '/' + this.session_key);
            },
        }
    }
</script>
