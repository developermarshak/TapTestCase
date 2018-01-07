$(function() {
    new FancyGrid({
        title: 'Clicks',
        renderTo: 'table',
        width: 800,
        height: 800,
        data: data,
        defaults: {
            type: 'string',
            width: 100,
            editable: false,
            sortable: true,
            filter: {
                header: true,
                emptyText: 'Search'
            }
        },
        clicksToEdit: false,
        columns: [{
            index: 'id',
            title: 'ID'
        }, {
            index: 'ua',
            title: 'User-agent'
        }, {
            index: 'ip',
            title: 'IP'
        }, {
            index: 'referer',
            title: 'Referer'
        }, {
            index: 'param1',
            title: 'param1'
        }, {
            index: 'param2',
            title: 'param2'
        }, {
            index: 'error',
            title: 'Count error',
            type: 'number'
        }, {
            index: 'bad_domain',
            title: 'Is bad domain',
            type: 'checkbox'
        }]
    });

});