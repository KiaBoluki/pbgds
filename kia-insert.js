jQuery(document).ready(function ($) {
    $('form input[type="submit"').on('click', function (e) {
        e.preventDefault();
        let form = $('form#kia-insert-new-user-form');
        let formInputs = $('form#kia-insert-new-user-form input');
        let resp = $('#kia-response-container p#kia-response');
        resp.html('<div class="spinner-border text-success"></div>');
        $.ajaxSetup({
            'cache-control': 'no-store',
            'cache-control': 'no-cache',

        });
        $.ajax({

            url: form.attr('action'),
            type: 'POST',
            data: form.serializeArray(),
            cache: false,
            headers: {
                'cache-control': 'no-store',
                'cache-control': 'no-cache',
            },
            success: function (response) {
                let cls = '';
                
                resp.html("");
                try{
                response = JSON.parse(response)}
                catch(ex)
                {
                    console.log(ex);
                }
                if (!response) { console.log('No response.'); return false;}
                if ( response.status == '0' ) {
                    cls = 'danger';
                    $.each(response.msg, function (key, value) {
                        resp.append(`<p class="alert alert-${cls} text-left">${value}</p>`);
                    })
                    return ; 
                }

                cls = 'success'
                resp.append(`<p class="alert alert-${cls}">${response.msg}</p>`);
                $.each(formInputs, function(key, item){
                    if($(item).attr('type') != 'submit')
                    $(item).val("");
                })
                return;
            }
        });
    })
}
)
