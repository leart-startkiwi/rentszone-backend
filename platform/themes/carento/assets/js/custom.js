(function ($) {
    ('use strict')

    $(document).ready(function () {
        $('#js-box-search-advance .box-top-search .btn-click').on('click', function () {
            $('#js-box-search-advance .box-top-search input').val($(this).data('tab'))
        })
        $('#js-box-search-advance #pick-up-location .dropdown-menu .dropdown-item').on('click', function (e) {
            $('#js-box-search-advance #pick-up-location .location-search span').text($(this).text())
            $('#js-box-search-advance #pick-up-location input').val($(this).data('id'))
        })
        $('#js-box-search-advance #drop-off-location .dropdown-menu .dropdown-item').on('click', function (e) {
            $('#js-box-search-advance #drop-off-location input').val($(this).data('id'))
        })

        $('.car-detail-share .dropdown-item').on('click', function() {
            if ($(this).closest('.dropdown-item')) {
                window.open($(this).closest('.dropdown-item').attr('href'), '_blank');
            }
        })

        $('.dropdown-login .dropdown-item').on('click', function() {
            if ($(this).closest('.dropdown-item')) {
                window.location.href = $(this).closest('.dropdown-item').attr('href')
            }
        })

        try {
            new Tobii()
        } catch (err)  {
            console.log(err)
        }

        Number.prototype.format_price = function (n, x) {
            let currencies = window.currencies || {}
            if (!n) {
                n = window.currencies.number_after_dot !== undefined ? window.currencies.number_after_dot : 2
            }
            let re = '\\d(?=(\\d{' + (x || 3) + '})+$)'
            let priceUnit = ''
            let price = this
            if (window.currencies.show_symbol_or_title) {
                priceUnit = window.currencies.symbol || window.currencies.title
            }
            if (window.currencies.display_big_money) {
                if (price >= 1000000 && price < 1000000000) {
                    price = price / 1000000
                    priceUnit = window.currencies.million + (priceUnit ? ' ' + priceUnit : '')
                } else if (price >= 1000000000) {
                    price = price / 1000000000
                    priceUnit = window.currencies.billion + (priceUnit ? ' ' + priceUnit : '')
                }
            }
            price = price.toFixed(Math.max(0, ~~n))
            price = price.toString().split('.')
            price =
                price[0].toString().replace(new RegExp(re, 'g'), '$&' + window.currencies.thousands_separator) +
                (price[1] ? currencies.decimal_separator + price[1] : '')
            if (currencies.show_symbol_or_title) {
                if (currencies.is_prefix_symbol) {
                    price = priceUnit + price
                } else {
                    price = price + priceUnit
                }
            }
            return price
        }

        let submitForm = (e, element = null) => {
            let $this = ''

            if (element) {
                $this = element
            } else if (e) {
                $this = $(e.currentTarget)
            }

            let $form = $this.closest('form')
            if (!$form.length && $this.prop('form')) {
                $form = $($this.prop('form'))
            }

            if ($form.length) {
                $form.trigger('submit')
            }
        }

        const handleError = (data) => {
            if (typeof data.errors !== 'undefined' && data.errors.length) {
                handleValidationError(data.errors)
            } else if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        handleValidationError(data.responseJSON.errors)
                    }
                } else if (typeof data.responseJSON.message !== 'undefined') {
                    showError(data.responseJSON.message)
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            showError(item)
                        })
                    })
                }
            } else {
                showError(data.statusText)
            }
        }

        const handleValidationError = (errors) => {
            let message = ''
            $.each(errors, (index, item) => {
                if (message !== '') {
                    message += '<br />'
                }
                message += item
            })
            showError(message)
        }

        window.showAlert = (messageType, title, message) => {
            if (messageType && message !== '') {
                let alertId = Math.floor(Math.random() * 1000)

                let type = null
                let colorType = null
                title = title || 'Alert'

                switch (messageType) {
                    case 'alert-success':
                        type = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="45px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`

                        colorType = 'success'
                        break

                    case 'status':
                        type = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="45px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`

                        colorType = 'success'
                        break

                    case 'alert-danger':
                        type = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="45px">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`

                        colorType = 'error'
                        break
                }

                let html = `
                <div class="toast ${colorType}" id="${alertId}">
                    <div class="outer-container">
                        ${type}
                    </div>
                    <div class="inner-container">
                        <p>${title}</p>
                        <p>${message}</p>
                    </div>
                    <a class="close-toast" >&times;</a>
                </div>
            `

                $('#alert-container')
                    .append(html)
                    .ready(() => {
                        window.setTimeout(() => {
                            $(`#alert-container #${alertId}`).remove()
                        }, 6000)
                    })

                $('#alert-container').on('click', '.close-toast', function (event) {
                    event.preventDefault()
                    $(this).closest('.toast').remove()
                })
            }
        }

        const loading = $('.loading-ajax')

        loading.hide()

        const initUiSlider = (element) => {
            let carMaxRentalRate = parseInt(element.data('maxRentalRateRange'))

            if (element.length > 0) {
                let moneyFormat = wNumb({
                    decimals: 0,
                    thousand: ',',
                    prefix: '',
                })
                const rentalRateTo = parseInt($('input[name="rental_rate_to"]').val())

                noUiSlider.create(element[0], {
                    start: rentalRateTo,
                    tooltips: [wNumb({ decimals: 0 })],
                    step: 1,
                    range: {
                        min: 0,
                        max: carMaxRentalRate,
                    },
                    format: moneyFormat,
                    connect: 'lower',
                })

                const rate = $(element[0]).attr('data-currency-rate') || 1

                const toNumber = str => Number(str.replace(/,/g, ''));

                // Set visual min and max values and also update value hidden form inputs
                element[0].noUiSlider.on('update', function (values, handle) {
                    $('.value-money').val(values[0])

                    $('.noUi-tooltip').html((toNumber($('.box-slider-range').find('input[name="rental_rate_to"]').val()) * parseFloat(rate)).toFixed())
                })

                element[0].noUiSlider.on('update', function (values) {
                    $('input[name="rental_rate_from"]').val(moneyFormat.from(values[1]))
                    $('input[name="rental_rate_to"]').val(moneyFormat.from(values[0]))
                })

                element[0].noUiSlider.on('change', function (values, handle, e) {
                    submitForm(e, $(document).find('.box-value-price'))
                })

                element[0].noUiSlider.on('slide', function (values) {
                    $(document).find('.salary-range').text(`${values[0]} - ${values[1]}`)
                })
            }
        }

        const initSelect2 = (element) => {
            element.select2({
                minimumInputLength: 0,
                placeholder: element.data('placeholder'),
                tags: true,
                ajax: {
                    url: $(this).data('url') || `${window.siteUrl}/ajax/locations`,
                    dataType: 'json',
                    delay: 500,
                    type: 'GET',
                    data: function (params) {
                        return {
                            k: params.term, // search term
                            page: params.page || 1,
                            type: $(this).data('location-type'),
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.data[0], function (item) {
                                return {
                                    text: item.name,
                                    id: item.name,
                                    data: item,
                                }
                            }),
                            pagination: {
                                more: params.page * 10 < data.total,
                            },
                        }
                    },
                },
            })
        }

        initUiSlider($('#slider-range'))
        initSelect2($('.select-location'))

        $(() => {
            window.carListMaps = {}

            let CarListApp = {}

            CarListApp.$formSearch = $('#cars-filter-form')
            CarListApp.carListing = '.cars-listing'
            CarListApp.$carListing = $(CarListApp.carListing)
            CarListApp.parseParamsSearch = function (query, includeArray = false) {
                let pairs = query || window.location.search.substring(1)
                let re = /([^&=]+)=?([^&]*)/g
                let decodeRE = /\+/g // Regex for replacing addition symbol with a space
                let decode = function (str) {
                    return decodeURIComponent(str.replace(decodeRE, ' '))
                }

                let params = {},
                    e
                while ((e = re.exec(pairs))) {
                    let k = decode(e[1]),
                        v = decode(e[2])
                    if (k.substring(k.length - 2) === '[]') {
                        if (includeArray) {
                            k = k.substring(0, k.length - 2)
                        }
                        ;(params[k] || (params[k] = [])).push(v)
                    } else params[k] = v
                }
                return params
            }

            CarListApp.changeInputInSearchForm = function (parseParams) {
                CarListApp.$formSearch.find('input, select, textarea').each(function (e, i) {
                    CarListApp.changeInputInSearchFormDetail($(i), parseParams)
                })

                $(':input[form=cars-filter-form]').each(function (e, i) {
                    CarListApp.changeInputInSearchFormDetail($(i), parseParams)
                })
            }

            CarListApp.changeInputInSearchFormDetail = function ($el, parseParams) {
                const name = $el.attr('name')
                let value = parseParams[name] || null
                const type = $el.attr('type')
                switch (type) {
                    case 'checkbox':
                    case 'radio':
                        $el.prop('checked', false)
                        if (Array.isArray(value)) {
                            $el.prop('checked', value.includes($el.val()))
                        } else {
                            $el.prop('checked', !!value)
                        }
                        break
                    default:
                        if ($el.is('[name=max_price]')) {
                            $el.val(value || $el.data('max'))
                        } else if ($el.is('[name=min_price]')) {
                            $el.val(value || $el.data('min'))
                        } else if ($el.val() !== value) {
                            $el.val(value)
                        }
                        break
                }
            }

            CarListApp.convertFromDataToArray = function (formData) {
                let data = []
                formData.forEach(function (obj) {
                    if (
                        obj.name === 'rental_rate_to' &&
                        parseInt(obj.value) === parseInt($('input[name="rental_rate_to"]').data('default-value'))
                    ) {
                        return
                    }

                    if (obj.name === '_token') return

                    if (obj.value) {
                        // break with price
                        if (['min_price', 'max_price'].includes(obj.name)) {
                            const dataValue = CarListApp.$formSearch
                                .find('input[name=' + obj.name + ']')
                                .data(obj.name.substring(0, 3))
                            if (dataValue === parseInt(obj.value)) {
                                return
                            }
                        }
                        data.push(obj)
                    }
                })

                return data
            }

            CarListApp.carsFilter = function () {
                let ajaxSending = null
                $(document).on('submit', '#cars-filter-form', function (e) {
                    e.preventDefault()

                    if ($(document).find('.sidebar-filter-mobile').hasClass('active')) {
                        $(document).find('.sidebar-filter-mobile').removeClass('active')

                        $('html, body').animate({
                            scrollTop: $('.car-content-section').offset().top - 150,
                        })
                    }

                    if (ajaxSending) {
                        ajaxSending.abort()
                    }

                    const $form = $(e.currentTarget)
                    let formData = $form.serializeArray()
                    let data = CarListApp.convertFromDataToArray(formData)
                    let uriData = []
                    let location = window.location
                    let nextHref = location.origin + location.pathname

                    $.urlParam = function (name) {
                        let results = new RegExp('[?&]' + name + '=([^&#]*)').exec(window.location.search)

                        return results !== null ? results[1] || 0 : false
                    }
                    if ($.urlParam('limit')) {
                        data.push({ name: 'limit', value: parseInt($.urlParam('limit')) })
                    }

                    // Paginate
                    const $elPage = CarListApp.$carListing.find('input[name=page]')
                    if ($elPage.val()) {
                        data.push({ name: 'page', value: $elPage.val() })
                    }

                    data.map(function (obj) {
                        if (obj.name === 'rental_rate_to') {
                            obj.value = Number(obj.value.replace(/[^0-9.-]+/g, ''))
                        }

                        if (uriData.find((item) => item.includes(obj.name))) {
                            return
                        }

                        if (
                            obj.name === 'rental_rate_to' &&
                            parseInt($('input[name="rental_rate_to"]').data('default-value')) === parseInt(obj.value)
                        ) {
                            return
                        }

                        if (obj.name === 'rental_rate_from' && !parseInt(obj.value)) {
                            return
                        }

                        if (obj.name === 'rental_rate_to' && !parseInt(obj.value)) {
                            return
                        }

                        if (obj.name === 'page' && parseInt(obj.value) === 1) {
                            return
                        }

                        uriData.push(encodeURIComponent(obj.name) + '=' + obj.value)
                    })

                    if (uriData && uriData.length) {
                        nextHref += `?${uriData.join('&')}`
                    }

                    ajaxSending = $.ajax({
                        url: $form.attr('action'),
                        type: 'GET',
                        data,
                        beforeSend: function () {
                            // Show loading before sending
                            $('#loading').css('display', 'flex')
                            $('.car-items').css('opacity', 0.2)
                        },
                        success: function ({ error, data, additional, message }) {
                            if (error) {
                                showError(message || 'Opp!')

                                return
                            }

                            CarListApp.$carListing.html(data)

                            $form.closest('.filter-section').html($(additional.filters_html).html())

                            if (additional?.message) {
                                CarListApp.$carListing
                                    .closest('.cars-listing-container')
                                    .find('.showing-of-results')
                                    .html(additional.message)
                            }

                            if (nextHref !== window.location.href) {
                                window.history.pushState(data, message, nextHref)
                            }
                        },
                        error: function (error) {
                            if (error.statusText === 'abort') {
                                return // ignore abort
                            }
                            handleError(error)
                        },
                        complete: function () {
                            updateUrlResetFilter()
                            setTimeout(function () {
                                $('#loading').css('display', 'none')
                                $('.loading-ajax').hide()
                                $('.car-items').css('opacity', 1)
                            }, 500)
                        },
                    })
                })

                window.addEventListener(
                    'popstate',
                    function () {
                        window.location.reload()
                    },
                    false
                )

                $(document).on('click', CarListApp.carListing + ' .pagination a', function (e) {
                    e.preventDefault()
                    let aLink = $(e.currentTarget).attr('href')

                    if (!aLink.includes(window.location.protocol)) {
                        aLink = window.location.protocol + aLink
                    }

                    let url = new URL(aLink)
                    let page = url.searchParams.get('page')
                    CarListApp.$carListing.find('input[name=page]').val(page)
                    CarListApp.$formSearch.trigger('submit')
                })
            }

            CarListApp.carsFilter()

            $(document).on('change', '.submit-form-filter', function (e) {
                if (e.target.name === 'location') {
                    $('input[name=location]').val(e.target.value)
                }

                CarListApp.$formSearch.find('input[name="page"]').val(1)
                submitForm(e)
            })

            String.prototype.interpolate = function (params) {
                const names = Object.keys(params)
                const vals = Object.values(params)
                return new Function(...names, `return \`${this}\`;`)(...vals)
            }
            let $templatePopup = $('#traffic-popup-map-template').html()

            if ($('.cars-list-sidebar').length) {
                if ($('.cars-list-sidebar').is(':visible')) {
                    CarListApp.initMaps($('.cars-list-sidebar').find('.cars-list-map'))
                }

                $(window).on('resize', function () {
                    if ($('.cars-list-sidebar').is(':visible')) {
                        CarListApp.initMaps($('.cars-list-sidebar').find('.cars-list-map'))
                    }
                })
            }

            CarListApp.setCookie = function (cname, cvalue, exdays) {
                let d = new Date()
                let siteUrl = window.siteUrl

                if (!siteUrl.includes(window.location.protocol)) {
                    siteUrl = window.location.protocol + siteUrl
                }

                let url = new URL(siteUrl)
                d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000)
                let expires = 'expires=' + d.toUTCString()
                document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/' + '; domain=' + url.hostname
            }
        })

        $(document).on('click', '.layout-car', function (e) {
            e.preventDefault()

            $('#cars-filter-form > input[name=layout]').val($(this).data('layout'))
            $('#cars-filter-form').submit()
        })

        $(document).on('click', '.dropdown-sort-by', function (e) {
            e.preventDefault()

            $('#cars-filter-form input[name=sort_by]').val($(this).data('sortBy'))
            $('#cars-filter-form').submit()
        })

        $(document).on('click', '.per-page-item', function (e) {
            e.preventDefault()

            $('#cars-filter-form input[name=per_page]').val($(this).data('perPage'))
            $('#cars-filter-form').submit()
        })

        $(document).on('change', '.box-filters-sidebar.vehicle-condition select', function (e) {
            e.preventDefault()

            $('#cars-filter-form').submit()
        })

        $(document).on('click', '.car-filter-checkbox .link-see-more-js', function (e) {
            e.preventDefault();

            $(this).hide();
            $(this).parent().parent().find('.list-filter-checkbox li').css('display', 'flex');
            $(this).parent().find('.link-see-less').css('display', 'flex');
        })

        $(document).on('click', '.car-filter-checkbox .link-see-less', function (e) {
            e.preventDefault();

            $(this).hide();
            $(this).parent().parent().find('.list-filter-checkbox li:nth-child(n+6)').css('display', 'none');
            $(this).parent().find('.link-see-more-js').css('display', 'flex');
        })

        function updateUrlResetFilter() {
            const $btnResetFilter = $('.link-reset')

            if ($btnResetFilter.length) {
                $btnResetFilter.attr('href', window.location.href.split('?')[0])
            }
        }

        if (!$('.sidebar-filter-mobile').length && $('.btn-advanced-filter').length) {
            $('.btn-advanced-filter').hide()
        }

        updateUrlResetFilter()
    })

    $('.shortcode-cars .popular-categories ul li').on('click', function(event) {
        event.target.parentElement.parentElement.parentElement.querySelector("input").value = event.target.getAttribute("data-value");
        const ajaxUrl = $('#popular-vehicle-url').val();
        const limitItems = $('#popular-vehicle-limit').val();
        const category = $('#filter-popular-category input').val();
        const fuel = $('#filter-popular-fuel input').val();
        const order = $('#filter-popular-order input').val();
        const priceRange = $('#filter-popular-price input').val();
        const param = {
            limit: limitItems,
            category: category,
            fuel_type: fuel,
            order: order,
            price_range: priceRange,
        };

        ajaxSearchPopularVehicles(
            ajaxUrl,
            '#content-popular-vehicles',
            param
        );
    });

    function ajaxSearchPopularVehicles(url, elApply, param) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            data: param,
            beforeSend: function() {

            },
            success: (response) => {
                if (!response.error) {
                    $(elApply).html(response.data);
                    if (response.additional.total) {
                        $('#popular-vehicle-load-more').attr('style','');
                    } else {
                        $('#popular-vehicle-load-more').attr('style','display:none !important');
                    }
                } else {
                    $(elApply).html('<p class="text-xl-medium neutral-500">No matching vehicle information found</p>');
                    $('#popular-vehicle-load-more').attr('style','display:none !important');
                }

                new Swiper(elApply + ' .swiper-group-2', {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next-2',
                        prevEl: '.swiper-button-prev-2',
                    },
                    autoplay: {
                        delay: 10000,
                    },
                    breakpoints: {
                        1199: {
                            slidesPerView: 2,
                        },
                        800: {
                            slidesPerView: 1,
                        },
                        400: {
                            slidesPerView: 1,
                        },
                        250: {
                            slidesPerView: 1,
                        },
                    },
                });
            },
            error: () => {
            },
            complete: () => {
            },
        })
    }

    $('.filter-brands-by-alphabet').on('click', '[data-bb-toggle="filter-brands"]', function() {
        const currentTarget = $(this)

        let currentValue = currentTarget.attr('data-bb-value')

        if (currentTarget.hasClass('active')) {
            currentTarget.removeClass('active')

            currentValue = null
        } else {
            $('[data-bb-toggle="filter-brands"]').each(function() {
                $(this).removeClass('active')
            })

            currentTarget.addClass('active')
        }

        $('[data-bb-toggle="brand-item"]').each(function() {
            let currentElement = $(this)

            let currentElementValue = currentElement.attr('data-bb-value')

            if (! currentValue) {
                currentElement.show()
                return
            }

            if (currentElementValue === currentValue) {
                currentElement.show()
            } else {
                currentElement.hide()
            }
        })

        $(document)
            .on('submit', '.bb-car-rentals-message-form', function (event) {
                event.preventDefault()
                event.stopPropagation()

                const $form = $(this)
                const $button = $form.find('button[type=submit]')

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: $form.prop('action'),
                    data: new FormData($form[0]),
                    contentType: false,
                    processData: false,
                    beforeSend: () => $button.addClass('btn-loading'),
                    success: ({ error, message }) => {
                        if (!error) {
                            $form[0].reset()
                            Theme.showSuccess(message)
                        } else {
                            Theme.showError(message)
                        }
                    },
                    error: (error) => {
                        Theme.handleError(error)
                    },
                    complete: () => {
                        if (typeof refreshRecaptcha !== 'undefined') {
                            refreshRecaptcha()
                        }

                        $button.removeClass('btn-loading')
                    },
                })
            })
    })
})(jQuery)
