!function($) {
    "use strict";
    $(function() {
        $("#cmb2-metabox-migration #migration-start").on("click", function(event) {
            event.preventDefault(), $("#migration-state").show();
            const runMigration = data => {
                $.post(cb_ajax.ajax_url, {
                    _ajax_nonce: cb_ajax.nonce,
                    action: "start_migration",
                    data: data
                }, function(data) {
                    let allComplete = !0;
                    $.each(data, function(index, value) {
                        $("#" + index + "-count").text(value.index), "0" == value.complete && (allComplete = !1);
                    }), allComplete ? $("#migration-done").show() : runMigration(data);
                });
            };
            runMigration(!1);
        });
    });
}(jQuery), function($) {
    "use strict";
    $(function() {
        const arrayDiff = function(array1, array2) {
            var newItems = [];
            return jQuery.grep(array2, function(i) {
                -1 == jQuery.inArray(i, array1) && newItems.push(i);
            }), newItems;
        }, hideFieldset = function(set) {
            $.each(set, function() {
                $(this).parents(".cmb-row").hide();
            });
        }, showFieldset = function(set) {
            $.each(set, function() {
                $(this).parents(".cmb-row").show();
            });
        };
        if ($("#cmb2-metabox-cb_timeframe-custom-fields").length) {
            const timeframeRepetitionInput = $("#timeframe-repetition"), typeInput = $("#type"), gridInput = $("#grid"), weekdaysInput = $("#weekdays1"), startTimeInput = $("#start-time"), endTimeInput = $("#end-time"), repConfigTitle = $("#title-timeframe-rep-config"), repetitionStartInput = $("#repetition-start"), repetitionEndInput = $("#repetition-end"), fullDayInput = $("#full-day"), createBookingCodesInput = $("#create-booking-codes"), bookingCodesList = $("#booking-codes-list"), maxDaysSelect = $(".cmb2-id-timeframe-max-days"), repSet = [ repConfigTitle, fullDayInput, startTimeInput, endTimeInput, weekdaysInput, repetitionStartInput, repetitionEndInput, gridInput ], noRepSet = [ fullDayInput, startTimeInput, endTimeInput, gridInput, repetitionStartInput, repetitionEndInput ], repTimeFieldsSet = [ gridInput, startTimeInput, endTimeInput ], bookingCodeSet = [ createBookingCodesInput, bookingCodesList ], showRepFields = function() {
                showFieldset(repSet), hideFieldset(arrayDiff(repSet, noRepSet));
            }, showNoRepFields = function() {
                showFieldset(noRepSet), hideFieldset(arrayDiff(noRepSet, repSet));
            }, uncheck = function(checkboxes) {
                $.each(checkboxes, function() {
                    $(this).prop("checked", !1);
                });
            }, handleTypeSelection = function() {
                2 == $("option:selected", typeInput).val() ? maxDaysSelect.show() : maxDaysSelect.hide();
            };
            handleTypeSelection(), typeInput.change(function() {
                handleTypeSelection();
            });
            const handleFullDaySelection = function() {
                $("option:selected", timeframeRepetitionInput).val();
                fullDayInput.prop("checked") ? (gridInput.prop("selected", !1), hideFieldset(repTimeFieldsSet)) : showFieldset(repTimeFieldsSet);
            };
            handleFullDaySelection(), fullDayInput.change(function() {
                handleFullDaySelection();
            });
            const handleRepetitionSelection = function() {
                const selectedType = $("option:selected", timeframeRepetitionInput).val();
                selectedType ? ("norep" == selectedType ? showNoRepFields() : showRepFields(), "w" == selectedType ? weekdaysInput.parents(".cmb-row").show() : (weekdaysInput.parents(".cmb-row").hide(), 
                uncheck($("input[name*=weekdays]"))), handleFullDaySelection()) : (hideFieldset(noRepSet), 
                hideFieldset(repSet));
            };
            handleRepetitionSelection(), timeframeRepetitionInput.change(function() {
                handleRepetitionSelection();
            });
            const handleBookingCodesSelection = function() {
                let repStart = repetitionStartInput.val(), repEnd = repetitionEndInput.val(), fullday = fullDayInput.prop("checked"), type = typeInput.val();
                repStart && repEnd && fullday && 2 == type ? showFieldset(bookingCodeSet) : hideFieldset(bookingCodeSet);
            };
            handleBookingCodesSelection();
            const bookingCodeSelectionInputs = [ repetitionStartInput, repetitionEndInput, fullDayInput, typeInput ];
            $.each(bookingCodeSelectionInputs, function(key, input) {
                input.change(function() {
                    handleBookingCodesSelection();
                });
            });
        }
    });
}(jQuery);