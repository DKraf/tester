"use strict"
$('#user_start_testing').fadeOut();

$(document).ready(function () {
    /**
     * Таймер
     */
    $('#button_test_starting').on( "click",function () {
        console.log($('#time_for_testing_view').text())

        $('#test_start_button_div').fadeOut()
        $('#user_start_testing').fadeIn()
        var min = $('#time_for_testing').val(),
            min_end = min,
            _Seconds = min * 60,
            int,
            sec = 60;
        min--
        int = setInterval(function () { // запускаем интервал
            if (_Seconds > 0) {
                if (sec == 0) {
                    sec = 60;
                    min--
                }
                sec--
                _Seconds--;
                $('.seconds').text(_Seconds);
                $('#time_for_testing_view').text('До окончания теста осталось: ' + min + 'мин ' + sec + 'сек')
                $('#time_for_testing').val(min_end - min)
                console.log(sec)

            } else {
                $("#end_test_for_end_time" ).submit();
            }
        }, 1000);
    });


    /**
     * сайтбар
     * @type {Element}
     */
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    /**
     * скрываем notification если активен
     */
    if ($('.alert').css('display', 'flex')) {
        setTimeout(function (){
            $('.alert').fadeOut(300);
        },4000)
    }

    /**
     * toasts notification
     * @param message
     * @returns {string}
     */
    function toasts(message, color) {
        return (
            `<div class="toast toast-notification-top align-items-center text-white bg-${color} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body text-center">
                  ${message}
                </div>
            </div>`
        )
    }

    /**
     * таймер закрытия блока toasts
     * @param block
     * @param time
     */
    function timeClose(block, time) {
        setTimeout(()=> {
            $(block).fadeOut("slow", function () {
                $(this).remove();
            })
        }, time)
    }

    /**
     * validation step edit
     * @param step
     * @returns {boolean}
     */
    function validationEditStep(step) {
        let message;
        let isValid = true;
        let checkboxes;
        if (step == '3') {
            checkboxes = $('.material:checkbox:checked')
            if (!checkboxes.length) {
                message = 'Не установлен чекбокс';
                if ($('.toast-notification-top').length) {
                    $('.toast-notification-top').remove()
                    $('body').append(toasts(message, 'danger'));
                } else {
                    $('body').append(toasts(message, 'danger'));
                }
                timeClose($('.toast-notification-top'),4000)
                isValid = false
            }
        }
        $(`.edit-step-${step}`).find(`[data-valid='true']`).each(function (i, item) {
            if (item.value === '') {
                message = 'Поля отмеченные (*) обязательны';
                if ($('.toast-notification-top').length) {
                    $('.toast-notification-top').remove()
                    $('body').append(toasts(message, 'danger'));
                } else {
                    $('body').append(toasts(message, 'danger'));
                }
                timeClose($('.toast-notification-top'),4000)
                isValid = false
            }
        })
        return isValid;
    }

})
