
$(document).ready(function(){
    $(".delete-btn").click(deleteOnclick);
    $('#formAddDomain').submit(function(e){
        e.preventDefault();
        addDomain($(this).find('#domain').val());
    });
});

function deleteOnclick(event){
    var element = event.target;
    var $tr = $(element).parent().parent();
    var id = $tr.data('id');
    deleteDomain(id, $tr);
}
function deleteDomain(id, element){
    $.ajax({
             method: "DELETE",
             url: '/bad_domains/'+id
        })
        .done(function(data, textStatus, jqXHR){
            $(element).remove();
        })
        .fail(function(jqXHR){
            if(jqXHR.status === 404){
                $(element).remove();
                return;
            }
            alert('Element not deleted. Internal server error');
        });
}

function addDomain(name){
    $.ajax({
        method: 'POST',
        url: '/bad_domains/',
        data: {name: name}
    })
        .done(function(data, textStatus, jqXHR){
            var link = jqXHR.getResponseHeader('location');
            addDomainElement(link);
        })
        .fail(function(jqXHR){
            if(jqXHR.status === 422){
                alert(jqXHR.responseJSON.name[0]);
                return;
            }
            alert('Element not add. Internal server error');
        });
}

function addDomainElement(link){
    $.ajax({
        method: 'GET',
        url: link
    })
        .done(function(data){
            var $tr = $('<tr>').data('id', data.id);
            var $tdId = $('<td>').text(data.id);
            var $tdName = $('<td>').text(data.name);
            var $button = $('<button>');
            $button.addClass("delete-btn");
            $button.attr('type', "button");
            $button.text('Delete');
            $button.click(deleteOnclick);
            var $tdButton = $('<td>').append($button);
            $tr.append($tdId).append($tdName).append($tdButton);

            $('table').append($tr);
        })
        .fail(function(){
            alert('Element not found. Internal server error');
        })
}