window.addEventListener('DOMContentLoaded', event => {

    const button = document.body.querySelector('#actionButton');
    const input = document.body.querySelector('#actionInput');

    const copyButton = document.body.querySelector('#actionCopy');
    const copyInput = document.body.querySelector('#copyInput');

    const modal = document.body.querySelector('#actionModal');

    button.addEventListener("click", async function(e){
        const buttonValue = this.getAttribute('value');
        const inputURL = input.value;

        switch(buttonValue){
            case "generate":
                if(validURL(inputURL)){
                    const results = await generate(input.value);
                    if(results[0]['state']){
                        const actionModal = new bootstrap.Modal(modal);
                        copyInput.value = results[0]['error'];

                        const original = input.value;
                        input.value = "";
                        let placeholder = document.body.querySelector('#text_placeholder');
                        placeholder.innerHTML = original;
                        placeholder.href = original;

                        actionModal.show();
                    } else {
                        sendAlert('warning', results[0]['msg']);
                    }
                } else {
                    sendAlert('warning', "URL is invalid.");
                }
                break;
            case "count":
                if(inputURL.trim().length === 0){
                    sendAlert('warning', "Please type the short code.");
                    return false;
                }
                const results = await count(input.value);
                if(results !== null){
                    if(results[0]['state']){
                        const actionModal = new bootstrap.Modal(modal);
                        copyInput.value = results[0]['msg'];

                        const shortened = input.value;
                        input.value = "";
                        let placeholder = document.body.querySelector('#text_placeholder');
                        placeholder.innerHTML = shortened;
                        placeholder.href = shortened;

                        actionModal.show();
                    } else {
                        sendAlert('warning', results[0]['msg']);
                    }
                } else {
                    sendAlert('warning', "Something went wrong, try again later.");
                }
                
                break;
        }
        
    });


    copyButton.addEventListener("click", function(e) {
        copyToClipboard(copyInput);
    });

    function copyToClipboard(element){
        element.select();
        element.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(element.value);
    }

    function validURL(string){
        if(string.trim().length === 0){
            return false;
        }
        return true;
    }

    async function generate(url){
        return await postDetails(url, 'generate');
    }

    async function count(url){
        return await postDetails(url, 'count');
    }
    
    async function getDetails(id, model) {
        let res = null;
        let response = await fetch("/get/" + model, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(async response => {
            const details = await response.json();
            res = details;
        });
        return res;
    }

    async function postDetails(url, model) {
        let res = null;
        let response = await fetch("/post/" + model, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                url: url,
                model: model
            })
        }).then(async response => {
            const details = await response.json();
            res = details;
        });
        return res;
    }

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    
    function sendAlert(type, message){
        switch(type){
            case "success":
                toastr.success(message);
                break;
            case "error":
                toastr.error(message);
                break;
            case "info":
                toastr.info(message);
                break;
            case "warning":
                toastr.warning(message);
                break;
            case "default":
                toastr.info(message);
                break;
        }
    }

});