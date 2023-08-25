<script src="http://sunnywalker.github.io/jQuery.FilterTable/jquery.filtertable.min.js"></script>
<script>
$(document).ready(function() {
        var stripeTable = function(table) { //stripe the table (jQuery selector)
            table.find('tr').removeClass('striped').filter(':visible:even').addClass('striped');
        };
        $('table').filterTable({
            callback: function(term, table) { stripeTable(table); } //call the striping after every change to the filter term
        });
        stripeTable($('table')); //stripe the table for the first time
    });
</script>
