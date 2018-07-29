$(document).ready(function(){
    // When an event row is clicked open that event's details page
    $('.sessionName').click(function(){
        window.location = $(this).attr('href');
        return false;
    });

    $('#eventSearch').keyup(function(){
        searchTable("upcomingTable");
        searchTable("completedTable");
        searchTable("processedTable");
    });

    $('.glyphicon-edit').click(function() {
        window.location = $(this).attr('href');
        return false;
    });

    // Toggle the tables up and down
    $('.upcomingEventsHeader').click(function() {
        toggleCollapse($('#upcomingEventsTable'));
    });
    $('.completedEventsHeader').click(function() {
        toggleCollapse($('#completedEventsTable'));
    });
    $('.processedEventsHeader').click(function() {
        toggleCollapse($('#processedEventsTable'));
    });

    
    function toggleCollapse(table){
        table.children(".toggle").toggle();
        table.next().toggle();
        var glyph = table.find("i");
        glyph.toggleClass('glyphicon-collapse-up glyphicon-collapse-down');
    }



    // // Sort tables
    // $("#upcomingTable").tablesorter();
    // $("#completedTable").tablesorter();
    // $("#processedTable").tablesorter();

    
});


