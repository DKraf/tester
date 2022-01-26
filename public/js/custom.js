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

    $('#login_input').focusin(function() {
        let first = $('#firstname_input').val()
        let last = $('#lastname_input').val()
        let rand = (Math.floor(Math.random() * 100) + 1) + (Math.floor(Math.random() * 100) + 1);
        let login = transliterate(first) + '.' + transliterate(last) + rand
        $('#login_input').val(login)
    })

    function transliterate(word) {
        var answer = ""
            , a = {};

        a["Ё"] = "YO";
        a["Й"] = "I";
        a["Ц"] = "TS";
        a["У"] = "U";
        a["К"] = "K";
        a["Е"] = "E";
        a["Н"] = "N";
        a["Г"] = "G";
        a["Ш"] = "SH";
        a["Щ"] = "SCH";
        a["З"] = "Z";
        a["Х"] = "H";
        a["Ъ"] = "'";
        a["ё"] = "yo";
        a["й"] = "i";
        a["ц"] = "ts";
        a["у"] = "u";
        a["к"] = "k";
        a["е"] = "e";
        a["н"] = "n";
        a["г"] = "g";
        a["ш"] = "sh";
        a["щ"] = "sch";
        a["з"] = "z";
        a["х"] = "h";
        a["ъ"] = "'";
        a["Ф"] = "F";
        a["Ы"] = "I";
        a["В"] = "V";
        a["А"] = "a";
        a["П"] = "P";
        a["Р"] = "R";
        a["О"] = "O";
        a["Л"] = "L";
        a["Д"] = "D";
        a["Ж"] = "ZH";
        a["Э"] = "E";
        a["ф"] = "f";
        a["ы"] = "i";
        a["в"] = "v";
        a["а"] = "a";
        a["п"] = "p";
        a["р"] = "r";
        a["о"] = "o";
        a["л"] = "l";
        a["д"] = "d";
        a["ж"] = "zh";
        a["э"] = "e";
        a["Я"] = "Ya";
        a["Ч"] = "CH";
        a["С"] = "S";
        a["М"] = "M";
        a["И"] = "I";
        a["Т"] = "T";
        a["Ь"] = "'";
        a["Б"] = "B";
        a["Ю"] = "YU";
        a["я"] = "ya";
        a["ч"] = "ch";
        a["с"] = "s";
        a["м"] = "m";
        a["и"] = "i";
        a["т"] = "t";
        a["ь"] = "'";
        a["б"] = "b";
        a["ю"] = "yu";

        for (let i in word) {
            if (word.hasOwnProperty(i)) {
                if (a[word[i]] === undefined) {
                    answer += word[i];
                } else {
                    answer += a[word[i]];
                }
            }
        }
        return answer;
    }

    var modal = document.getElementById("myModal1");
    var img1 = document.getElementById("myImg1");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    var modal1 = document.getElementById("myModal2");
    var img2 = document.getElementById("myImg2");
    var modalImg1 = document.getElementById("img02");
    var captionText1 = document.getElementById("caption1");

    var modal2 = document.getElementById("myModal3");
    var img3 = document.getElementById("myImg3");
    var modalImg2 = document.getElementById("img03");
    var captionText2 = document.getElementById("caption2");

    var span1 = document.getElementById("span1");
    var span2 = document.getElementById("span2");
    var span3 = document.getElementById("span3");

    img1.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }

    img2.onclick = function(){
        modal1.style.display = "block";
        modalImg1.src = this.src;
        captionText1.innerHTML = this.alt;
    }

    img3.onclick = function(){
        modal2.style.display = "block";
        modalImg2.src = this.src;
        captionText2.innerHTML = this.alt;
    }

    span1.onclick = function() {
        modal.style.display = "none";
    }

    span2.onclick = function() {
        modal1.style.display = "none";
    }

    span3.onclick = function() {

        modal2.style.display = "none";
    }
})
