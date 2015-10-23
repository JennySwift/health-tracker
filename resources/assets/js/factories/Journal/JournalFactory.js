app.factory('JournalFactory', function ($http) {
    return {

        getJournalEntry: function ($sql_date) {
            return $http.get('api/journal/' + $sql_date);
        },
        filter: function () {
            var $typing = $("#filter-journal").val();
            var $url = 'select/filterJournalEntries';
            var $data = {
                typing: $typing
            };

            return $http.post($url, $data);
        },

        insertJournalEntry: function ($sql_date) {
            var $url = '/journal';
            var $text = $("#journal-entry").html();
            var $data = {
                date: $sql_date,
                text: $text
            };

            return $http.post($url, $data);
        },

        updateJournalEntry: function ($journal) {
            var $url = $journal.path;
            var $text = $("#journal-entry").html();
            var $data = {
                text: $text
            };

            return $http.put($url, $data);
        }
    };
});