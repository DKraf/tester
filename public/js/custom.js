"use strict"
$(document).ready(function () {
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
     * Получение списка головных отделений Компаний
     */
    $('#branch').click(function () {
        if ($('#branch').is(':checked')) {
            if (this.value === '1') {
                $.ajax({
                    url: '/advertising/companies/main',
                    type: 'get',
                    dataType: 'json'
                }).then(function (result) {
                    // console.log(result)
                    let html = '<div class="mb-3 companies-block"><label for="main_company" class="form-label">Головная организация</label><select class="form-select" id="main_company" name="main_company"><option value="" selected>Выберите ГО</option>';
                    $.each(result, function (i, item) {
                         html += '<option value="'+item.id+'">'+item.name+'</option>'
                    })
                    html += '</select></div>';
                    $('.modal-body').append(html);
                }).catch(function (error){
                    console.log(error)
                });
            }
        } else {
            $('.companies-block').remove()
        }
    })

    /**
     * Запрос для проверки партнера по БИН
     */
    $('#get-partner-info').click(function (e) {
        e.preventDefault();
        const bin = $('.bin').val()
        if (!validationBin(bin)) return false;
        $.ajax({
            url: '/advertising/partner/' + bin,
            type: 'get',
            dataType: 'json'
        }).then(function (result) {
            let html = '';
            if (result.partner && !result.partner.products.QR) {
                html += '<p class="fst-italic text-center text-danger">У данного Kaspi партнера нет продуктов</p>';
                $('.append-js').empty().append(html);
                $('.static-block').css('display', 'none')
                $('.btn-next').css('display', 'none')
                $('.step--2').empty()
                return false;
            }
            $('.append-js').empty().append(renderForm(result));
            $('.static-block').css('display','block')
            $('.btn-next').css('display','block').attr('data-step','1').attr('data-current-step','1');
            $('.step--2').empty().append(function () {
                    html += '<div class="form-group mb-3"><select data-valid="true" data-value="new" class="form-select statuses" name="status_id"><option selected value="">Выберите основной статус</option>'
                        for (let status of result.statuses) {
                            html += `<option value="${status.id}">${status.title}</option>`;
                        }
                    html += '</select></div>'
                return html;
            })
            sortedBranch();
        }).catch(function (error){
            console.log(error)
        });
    })

    /**
     * !!!!!!!!!!!! Рассмотреть !!!!!!!!!!!!!!!!
     * отлавливаем событие изменение инпут и очищаем форму
     */

    // $('input[name=bin]').on('input',function () {
    //     $('.append-js').empty();
    //     $('.static-block').css('display','none');
    // })

    /**
     * рендерим форму для создания заявки
     * @param data
     * @returns {string}
     */
    function renderForm(data)
    {
        let active;
        let flag;
        let form = '';
        if (data.hasOwnProperty('legal_entity') && !data.legal_entity) {
            $('.cities').css('display','block').children().attr('data-valid',true)
            form += '<p class="fst-italic text-center text-danger">Под данному БИН не зарегистрированно Юридическое лицо</p><div class="input-group mb-3"><input type="text" data-valid="true" name="temp_address" class="form-control" placeholder="Введите адрес точки"></div><div class="input-group mb-3"><input type="text" data-valid="true" name="temp_branch" class="form-control" placeholder="Введите наименование точки"></div>'
        } else if (data.hasOwnProperty('tax_deb') && !data.tax_deb) {
            $('.cities').css('display','block').children().attr('data-valid',true)
            form += '<p class="fst-italic text-center text-danger">По данному БИН найдены задолжности</p><div class="input-group mb-3"><input type="text" name="temp_address" data-valid="true" class="form-control" placeholder="Введите адрес точки"></div><div class="input-group mb-3"><input type="text" data-valid="true" name="temp_branch" class="form-control" placeholder="Введите наименование точки"></div>'
        } else if (data.hasOwnProperty('partner') && !data.partner) {
            $('.cities').css('display','block').children().attr('data-valid',true)
            form += '<p class="fst-italic text-center text-danger">По данному БИН Kaspi-партнер не найден</p><div class="input-group mb-3"><input type="text" name="temp_address" data-valid="true" class="form-control" placeholder="Введите адрес точки"></div><div class="input-group mb-3"><input type="text" data-valid="true" name="temp_branch" class="form-control" placeholder="Введите наименование точки"></div>'
        } else {
            $('.cities').css('display','none').children().attr('data-valid',false)
            form += `<div class="input-group mb-3"><input readonly type="text" name="legal_address" class="form-control" value="${data.partner.legal_address}"></div>`;
            form += `<div class="input-group mb-3"><input readonly type="text" name="partner_name" class="form-control" value="${data.partner.name}"></div>`;
            form += `<ul class="list-group">`
            for (const [key, value] of Object.entries(data.partner.products)) {
                if (value) active = 'active'; else active = '';
                if (value) flag = 1; else flag = 0;
                form += `<li class="list-group-item ${active}">${key}</li>`
                form += `<input name="${key}" type="hidden" value="${flag}">`
            }
            form += `</ul>`
            form += '<div class="input-group mb-3"><select data-valid="true" id="branches" name="branch_partner_name" class="form-select"><option value="" selected>Выберите точку</option>'
            for (let shop of data.partner.trade_points) {
                form += `<option value="${shop.city}, ${shop.branch_name}, ${shop.address}, ${shop.rfo_id}">${shop.city}, ${shop.branch_name} на ${shop.address}</option>`
            }
            form+=`</select></div>`;
        }
        return form;
    }

    /**
     * шаги формы создании заявки
     */

    $('.btn-next').click(function () {
        let step = $(this).attr('data-step');
        let currentStep = $(this).attr('data-current-step');
        if (!validationStep(currentStep)) return false;
        if (step === '1') {
            $('.step--1').css('display','none');
            $('.step--2').css('display','block');
            $(this).css('display','none').attr('data-step', '2').attr('data-current-step', '2');
        } else if (step === '2') {
            let form = '';
            const products = {
                  Pay: parseInt($("input[name=Pay]").val()),
                  QR: parseInt($("input[name=QR]").val()),
                  Credit: parseInt($("input[name=Credit]").val()),
                  RED: parseInt($("input[name=RED]").val())
            }
            $('.step--2').css('display','none');
            $.ajax({
                url: '/advertising/materials',
                type: 'get',
                data: {products: products},
                dataType: 'json'
            }).then(function (data) {
                form += '<div class="col-sm-12">'
                for (let [key, value] of Object.entries(data)) {
                    form +=`<p class="fs-5">${key}</p>`
                    for (let material of value) {
                        form += `<div class="d-flex flex-row align-items-center justify-content-between"><div class="mb-3 form-check"><input value="${material.id}" name="materials[${material.id}][material_id]" type="checkbox" class="form-check-input material" id="${key}-${material.id}"><label class="form-check-label" for="${key}-${material.id}">${material.name}</label></div></div>`
                    }
                }
                form += '<div class="mb-3"><label for="formFileSm" class="form-label">Выберите один или несколько фотографий</label><input multiple data-valid="true" class="form-control form-control-sm" name="material_photos[]" id="formFileSm" type="file"></div>'
                $('.step--3').empty().append(form);
            }).catch(function (error){
                console.log(error)
            });
            $('.step--3').css('display','block');
            $(this).css('display','block').removeAttr('data-step').attr('data-current-step', '3').attr('btn-status','ready').text('Создать');
        } else if ($(this).attr('btn-status') === 'ready') {
            $('#form-request').submit();
        }
    })

    /**
     * отлавдиваем событие change по кликнотому чекбоксу и отображаем поле каунт
     */

    $(document).on('change', '.material', function (e) {
        let form = `<div class="input-group input-group-sm mb-3 material-count-block material-count-${e.target.value}"><span class="input-group-text" id="inputGroup-sizing-sm">Количество</span><input data-valid="true" name="materials[${e.target.value}][material_count]" type="number" class="form-control" id="material-count"></div>`;
        if ($(this).is(':checked')) {
            $(this).parent().parent().append(form)
        } else {
            $(this).parent().parent().find('.material-count-'+e.target.value).remove()
        }
    })

    /**
     * получаем саб статусы
     */
    $(document).on('change', '.statuses', function (e) {
        let data_value;
        if ($(e.target).attr('data-value') === 'edit') {
            data_value = 'edit'
        } else {
            data_value = 'new'
        }
        if (e.target.value === '') {
            if ($(e.target).attr('data-value') === 'edit') {
                $('.edit-step-2').children()[1].remove();
                $('.edit-btn-next').css('display','none')
            } else {
                $('.step--2').children()[1].remove();
                $('.btn-next').css('display','none')
            }
            return false;
        }
        let form = '';
        if ($(e.target).attr('data-value') === 'edit') {
            $('.edit-btn-next').css('display','none')
        } else {
            $('.btn-next').css('display','none')
        }
        $.ajax({
            url: '/advertising/sub-statuses/'+e.target.value,
            type: 'get',
            dataType: 'json'
        }).then(function (data) {
            // console.log(data)
            if (data.length > 0) {
                form = `<div class="form-group mb-3"><select data-valid="true" data-value=${data_value} class="form-select sub_statuses" name="sub_status_id"><option value="" selected>Выберите дополнительный статус</option>`;
                for (let status of data) {
                    form += `<option value="${status.id}">${status.title}</option>`
                }
                form += '</select></div>'
                if ($(e.target).attr('data-value') === 'edit') {
                    if ($('.edit-step-2').children()[1]) {
                        $('.edit-step-2').children()[1].remove();
                        $('.edit-step-2').append(form)
                    } else {
                        $('.edit-step-2').append(form);
                    }
                } else {
                    if ($('.step--2').children()[1]) {
                        $('.step--2').children()[1].remove();
                        $('.step--2').append(form)
                    } else {
                        $('.step--2').append(form);
                    }
                }

            } else {
                if ($(e.target).attr('data-value') === 'edit') {
                    if ($('.edit-step-2').children()[1]) $('.edit-step-2').children()[1].remove();
                    $('.edit-btn-next').css('display','block').removeAttr('data-step').attr('btn-status','ready').text('Обновить');
                } else {
                    if ($('.step--2').children()[1]) $('.step--2').children()[1].remove();
                    $('.btn-next').css('display','block').removeAttr('data-step').attr('btn-status','ready').text('Создать');
                }
            }
        }).catch(function (error){
            console.log(error)
        });
    })


    /**
     * получаем статусы у которых есть зависимость расклеить рекламу (Promise)
     */
    function getPasteUp()
    {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/advertising/sub-statuses/get/paste-up',
                type: 'get',
            }).then(function (result) {
                resolve(result)
            }).catch(function (error){
                reject(error)
            });
        })
    }

    /**
     * выводим кнопку в зависимости от выбранного статуса (submit || next-btn)
     * Дожидаемся ответа сервера
     */
    $(document).on('change','.sub_statuses', async function (e) {
        let pasteUp;
        await getPasteUp().then((data)=>pasteUp=data);
        if (e.target.value === '') {
            if ($(e.target).attr('data-value') === 'edit') {
                $('.edit-btn-next').css('display','none')
            } else {
                $('.btn-next').css('display','none')
            }
        } else if ($.inArray(e.target.value, pasteUp) !== -1) {
            if ($(e.target).attr('data-value') === 'edit') {
                $('.edit-btn-next').removeAttr('btn-status').attr('data-step','2').attr('data-current-step', '2').css('display', 'block').text('Далее');
            } else {
                $('.btn-next').removeAttr('btn-status').attr('data-step','2').attr('data-current-step', '2').css('display', 'block').text('Далее');
            }
        } else {
            if ($(e.target).attr('data-value') === 'edit') {
                $('.edit-btn-next').css('display','block').removeAttr('data-step').attr('data-current-step', '2').attr('btn-status','ready').text('Обновить');
            } else {
                $('.btn-next').css('display','block').removeAttr('data-step').attr('data-current-step', '2').attr('btn-status','ready').text('Создать');
            }
        }
    })


    /**
     * сортируем по городам
     */
    function sortedBranch()
    {
        let options = $("#branches option");
        options.sort(function(a, b) {
            if (a.text && b.text === 'Выберите точку') return 1;
            if (a.text.split(",")[0] > b.text.split(",")[0]) return 1;
            else if (a.text.split(",")[0] < b.text.split(",")[0]) return -1;
            else return 0;
        });
        $("#branches").empty().append(options)
    }

    /**
     * отмечаем только не назначенные заявки (один или несколько), так же убираем
     */
    $('.request-checkbox').click(function () {
        if ($('.request-checkbox').is(':checked')) {
            $(this).attr('checked', true);
            $('.request-all').text('Снять все')
        } else {
            $(this).attr('checked', false);
            $('.request-all').text('Выбрать все')
        }
    })

    $('.request-all').click(function () {
        if ($('.request-checkbox').is(':checked')) {
            $('.request-checkbox').not("[disabled]").prop('checked', false);
            $(this).text('Выбрать все')
        } else {
            $('.request-checkbox').not("[disabled]").prop('checked', true);
            $(this).text('Снять все')
        }
    })
    // end

    /**
     * получаем компании и выводим модалку для выбора и включаем выбраные заявки в форму c таблицы
     */
    $('.assign').click(function () {
        let ids=[]
        let message;
        $('input:checkbox:checked').each(function() {
            ids.push(this.value);
        });
        if (ids.length === 0) {
            message = 'Не выбраны заявка(-и)';
            if ($('.toast-notification-top').length) {
                $('.toast-notification-top').remove()
                $('body').append(toasts(message, 'danger'));
            } else {
                $('body').append(toasts(message, 'danger'));
            }
            timeClose($('.toast-notification-top'),4000)
            return false
        }
        $('.modal-body>input[name="requests[]"]').remove();
        $('#company_id').empty();
        $.ajax({
            url: '/advertising/companies/all',
            type: 'get',
            dataType: 'json'
        }).then(function (result) {
            // console.log(result);
            $('#company_id').append(`<option disabled selected>Выберите компанию</option>`)
            result.forEach(item => {
                $('#company_id').append(`<option value="${item.id}">${item.name}</option>`)
            })
            $('#company-all').modal('show')
            ids.forEach(item=> {
                $('.modal-body').append(`<input name="requests[]" type="hidden" value="${item}">`)
            })
        }).catch(function (error){
            console.log(error)
        });

    })

    /**
     * inputMask phone && bin
     */
    $("#phone").inputmask('+7-(999)-999-9999');
    $(".bin").inputmask('999999999999',{"placeholder": ""});


    /**
     * #up button scroll auto top (кнопка для скрлинга вверх в заявках)
     */
    const btn = $('#up');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 200) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });
    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop:0}, '300');
    });

    /**
     * всплывающий блок с листингом кнопок
     */
    $(document).on('click','.more',function (e) {
        const phone = $(this).parent().prev().find('span').text();
        const url = $(this).parent().prev().parent().parent().attr('href');
        e.preventDefault();
        $("body").css("overflow","hidden");
        $('.more-list').fadeIn("slow").css('display','flex');
        $('.more-list ul').append(`<a class="call" href="tel:${phone}"><li class="list-group-item">Позвонить</li></a><a class="look" href="${url}"><li class="list-group-item">Просмотреть</li></a>`)
    })

    /**
     * отлавливаем событие клика на звонок и отправляем данные на бэк
     */

    $(document).on('click','.call', function () {
        const url_split = $('.look').attr('href').split('/');
        const request_id = url_split[url_split.length - 1];
        const _token = $('meta[name="csrf-token"]').attr('content')
        $.ajax({
            url: '/advertising/call-history/create',
            data: {request_id, _token},
            method: 'POST',
            dataType: 'json',
            success: function(data) {
                console.log(data)
            },
            error: function (error) {
                console.log(error)
            }
        });
    })

    /**
     * скрываем при нажатии вне блока списка
     */
    $('.more-list').click(function () {
        if ($(this).css('display',' flex')) {
            $(this).fadeOut("slow",function () {
                $('.more-list ul').empty();
                $('body').removeAttr('style');
            });
        }
    })

    /**
     * Ajax-подгрузка заявки при прокрутке страницы
     */
    function scrollMore() {
        let show_more = $('#show-more');
        let wt = $(window).scrollTop();
        let wh = $(window).height();
        let et = show_more.offset().top;
        let eh = show_more.outerHeight();
        let dh = $(document).height();
        if (wt + wh >= et || wh + wt === dh || eh + et < wh) {
            let page = show_more.attr('data-page');
            page++
            if (page <= show_more.attr('data-max')) {
                $('.loader img').show();
                $.ajax({
                    url: 'requests?page=' + page,
                    success: function(data) {
                        $('.requests-block ul').append($(data).find('.requests-block ul').html());
                        $('.loader img').hide();
                    },
                    error: function (error) {
                        console.log(error)
                        $('.loader img').hide();
                    }
                });
                show_more.attr('data-page', page);
            }
        }
    }

    /**
     * запускаем функцию для подгрузки заявок
     */
    $(window).scroll(function() {
        scrollMore();
    });


    /**
     * выводим инпут ввода для поиска заявок
     */

    $('.icons-block-search').click(function (e) {
        if (e.target.className === 'fa fa-search search-button') {
            $('.search-button').hide();
            $('.close-search-button').show();
            $('.search-input input').animate({
                width: "100%",
                opacity: 1
            })
        } else if (e.target.className === 'fa fa-close close-search-button') {
            $('.close-search-button').hide();
            $('.search-button').show();
            $('.search-input input').animate({
                width: "0%",
                opacity: 0
            }).val('')
            $('.search-requests').empty()
            $('.initial-requests').css('display', 'block')
        }
    })


    /**
     * поиск заявок
     */

    $('.search-input input').keyup(function (e) {
        let wait;
        let html;
        let user_id = $('.search-input input[name=user_id]').val();
        if (window.location.pathname.indexOf('waiting') === -1) {
            wait = 0
        } else {
            wait = 1
        }
        if (e.target.value === '') {
            $('.initial-requests').css('display', 'block')
            $('.search-requests').empty()
            return false;
        } else if (e.target.value.length < 4) {
            $('.initial-requests').css('display', 'block')
            $('.search-requests').empty()
            return false;
        } else {
            $('.initial-requests').css('display', 'none')
        }
        $('.loader img').css('display','block').parent().css({'display':'flex','justify-content': 'center'})
        $.ajax({
            url: '/advertising/requests/search',
            type: 'get',
            data: {word:e.target.value, waiting: wait, user_id:user_id},
            dataType: 'json'
        }).then(function (result) {
            if (result.length > 0) {
                for (let item of result) {
                    html += `<a href="/advertising/requests/details/${item.id}">
                            <li class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="ml-2">
                                        <h6 class="mb-0">${item.partner_contact_person}</h6>
                                        <div class="d-flex flex-row mt-1 text-black-50 date-time">
                                            <div><span class="ml-2">${item.partner_contact_phone}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <i class="fa fa-ellipsis-h more"></i>
                                </div>
                            </li>
                        </a>`
                }
                $('.search-requests').empty().append(html)
            } else {
                $('.search-requests').empty().append('<p class="text-center">По вашему запросу ничего не найдено!</p>')
            }
            $('.loader img').css('display','none')
        }).catch(function (error){
            console.log(error)
            $('.loader img').css('display','none')
        });

    })

    /**
     * событие очистки внутри search
     * @type {Element}
     */
    let search = document.querySelector(".search-input input");
    if (search) {
        search.addEventListener("search", function(event) {
            $('.initial-requests').css('display','block');
            $('.search-requests').empty()
        });
    }


    /**
     * апдейтим статус компаний
     */
    $('.change-status').change(function (e) {
        let status;
        const company_id = e.target.value;
        const _token = $('meta[name="csrf-token"]').attr('content');
        if ($(this).is(':checked')) status = 1; else status = 0;
        $.ajax({
            url: '/advertising/companies/change-status',
            data: {company_id, status, _token},
            method: 'POST',
            dataType: 'json',
            success: function(data) {
                $('.toast').fadeIn('show',function () {
                    $(this).removeClass('hide').addClass('show').find('.toast-body').empty().text(data.message)
                    setTimeout(function () {
                        $('.toast').fadeOut('slow', function () {
                            $(this).removeClass('show').addClass('hide')
                        })
                    },5000)
                });
            },
            error: function (error) {
                console.log(error)
            }
        });
    })

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
     * custom validation on BIN
     * @param bin
     */
    function validationBin(bin) {
        let message;
        switch (true) {
            case (bin === ''):
               message = 'Введите БИН';
                if ($('.toast-notification-top').length) {
                    $('.toast-notification-top').remove()
                    $('body').append(toasts(message, 'danger'));
                } else {
                    $('body').append(toasts(message, 'danger'));
                }
                timeClose($('.toast-notification-top'),4000)
                return false
            case (bin.length !== 12):
                message = 'БИН должен состоять из 12 цифр';
                if ($('.toast-notification-top').length) {
                    $('.toast-notification-top').remove()
                    $('body').append(toasts(message, 'danger'));
                } else {
                    $('body').append(toasts(message, 'danger'));
                }
                timeClose($('.toast-notification-top'),4000)
                return false
        }
        return true
    }

    /**
     * custom validation form step request
     * @param step
     */
    function validationStep(step) {
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
        $(`.step--${step}`).find(`[data-valid='true']`).each(function (i, item) {
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
     * показываем модалку для фотографий
     */

    $(".myImg").click(function () {
        $("#myModal").css('display','block');
        $("#img01").attr("src", $(this).attr("src"))
        $("#caption").text($(this).attr("alt"))
    })
    $(".close-img").click(function () {
        $("#myModal").fadeOut('slow',function () {
            $(this).css('display','none')
        })
    })


    /**
     * request edit step
     */

    $('.btn-edit-step').click(function () {
        let bin = $('input[name=bin]').val();
        let branch = $('input[name=branch_partner_name]').val();
        let branch_id = $('input[name=branch_partner_id]').val();
        $.ajax({
            url: '/advertising/partner/' + bin,
            type: 'get',
            dataType: 'json'
        }).then(function (result) {
            $('.details-block').remove();
            let html = ''
            if (result.hasOwnProperty('partner') && !result.partner.products.QR) {
                html += '<p class="fst-italic text-center text-danger">У данного Kaspi партнера нет продуктов</p>';
                $('.form-edit-block').children().empty().append(html)
                return false;
            }
            $('.form-edit-block').css('display','block')
            $('.append-js').empty().append(renderForm(result));
            if (branch_id !== '') {
                $('#branches').remove();
            }
            $('input[name=temp_address]').val(branch.split(',')[0].trim())
            $('input[name=temp_branch]').val(branch.split(',')[1].trim())
            $('.static-block').css('display','block')
            $('.edit-btn-next').css('display','block').attr('data-step','1').attr('data-current-step','1');
            $('.edit-step-2').empty().append(function () {
                html += '<div class="form-group mb-3"><select data-valid="true" data-value="edit" class="form-select statuses" name="status_id"><option selected value="">Выберите основной статус</option>'
                for (let status of result.statuses) {
                    html += `<option value="${status.id}">${status.title}</option>`;
                }
                html += '</select></div>'
                return html;
            })
            sortedBranch();
        }).catch(function (error){
            console.log(error)
        });
    })


    /**
     * edit step button
     */
    $('.edit-btn-next').click(function () {
        let step = $(this).attr('data-step');
        let currentStep = $(this).attr('data-current-step');
        if (!validationEditStep(currentStep)) return false;
        if (step === '1') {
            $('.edit-step-1').css('display','none');
            $('.edit-step-2').css('display','block');
            $(this).css('display','none').attr('data-step', '2').attr('data-current-step', '2');
        } else if (step === '2') {
            let form = '';
            const products = {
                Pay: parseInt($("input[name=Pay]").val()),
                QR: parseInt($("input[name=QR]").val()),
                Credit: parseInt($("input[name=Credit]").val()),
                RED: parseInt($("input[name=RED]").val())
            }
            $('.edit-step-2').css('display','none');
            $.ajax({
                url: '/advertising/materials',
                type: 'get',
                data: {products: products},
                dataType: 'json'
            }).then(function (data) {
                form += '<div class="col-sm-12">'
                for (let [key, value] of Object.entries(data)) {
                    form +=`<p class="fs-5">${key}</p>`
                    for (let material of value) {
                        form += `<div class="d-flex flex-row align-items-center justify-content-between"><div class="mb-3 form-check"><input value="${material.id}" name="materials[${material.id}][material_id]" type="checkbox" class="form-check-input material" id="${key}-${material.id}"><label class="form-check-label" for="${key}-${material.id}">${material.name}</label></div></div>`
                    }
                }
                form += '<div class="mb-3"><label for="formFileSm" class="form-label">Выберите один или несколько фотографий</label><input multiple data-valid="true" class="form-control form-control-sm" name="material_photos[]" id="formFileSm" type="file"></div>'
                $('.edit-step-3').empty().append(form);
            }).catch(function (error){
                console.log(error)
            });
            $('.edit-step-3').css('display','block');
            $(this).css('display','block').removeAttr('data-step').attr('data-current-step', '3').attr('btn-status','ready').text('Обновить');
        } else if ($(this).attr('btn-status') === 'ready') {
            $('#form-edit').submit();
        }
    })


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