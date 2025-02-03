<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo e($title); ?>
                    </h4>

                    <?php if (isset($custom_field)) { ?>
                    <a href="<?php echo admin_url('custom_fields/field'); ?>"
                        class="btn btn-primary"><?php echo _l('new_custom_field'); ?></a>
                    <?php } ?>
                </div>
                
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php $value = (isset($custom_field) ? $custom_field->name : ''); ?>
                        <?php echo render_input('name', 'custom_field_name', $value); ?>
                        
                        <div class="clearfix"></div>
                        
                        <?php $value = (isset($custom_field) ? $custom_field->field_order : ''); ?>
                        <?php echo render_input('field_order', 'custom_field_add_edit_order', $value, 'number'); ?>
                        
                        
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary" id="submitForm">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
var pdf_fields = <?php echo json_encode($pdf_fields); ?>;
var client_portal_fields = <?php echo json_encode($client_portal_fields); ?>;
var client_editable_fields = <?php echo json_encode($client_editable_fields); ?>;

$(function() {
    appValidateForm($('form'), {
        fieldto: 'required',
        name: 'required',
        type: 'required',
        bs_column: 'required',
        options: {
            required: {
                depends: function(element) {
                    return ['select', 'checkbox', 'multiselect'].indexOf($('#type').val()) > -1
                }
            }
        }
    }, function(form) {
        validateDefaultValueField().then(function(validation) {
            if (validation.valid) {
                $('#fieldto,#type').prop('disabled', false);

                $.post(form.action, $(form).serialize(), function(data) {
                    window.location.href = admin_url + 'custom_fields/field/' + data.id;
                }, 'json');
            }
        });

        return false;
    });

    $('select[name="fieldto"]').on('change', function() {
        var field = $(this).val();

        $.inArray(field, pdf_fields) !== -1 ? $('.show-on-pdf').removeClass('hide') : $('.show-on-pdf')
            .addClass('hide');

        if ($.inArray(field, client_portal_fields) !== -1) {
            $('.show-on-client-portal').removeClass('hide');
            $('.disalow_client_to_edit').removeClass('hide');

            if ($.inArray(field, client_editable_fields) !== -1) {
                $('.disalow_client_to_edit').removeClass('hide');
            } else {
                $('.disalow_client_to_edit').addClass('hide');
                $('.disalow_client_to_edit input').prop('checked', false);
            }
        } else {
            $('.show-on-client-portal').addClass('hide');
            $('.disalow_client_to_edit').addClass('hide');
        }
        if (field == 'tickets') {
            $('.show-on-ticket-form').removeClass('hide');
        } else {
            $('.show-on-ticket-form').addClass('hide');
            $('.show-on-ticket-form input').prop('checked', false);
        }

        field == 'customers' ? $('.customers_field_info').removeClass('hide') : $(
            '.customers_field_info').addClass('hide');
        field == 'items' ? $('.items_field_info').removeClass('hide') : $('.items_field_info').addClass(
            'hide');
        field == 'company' ? $('.company_field_info').removeClass('hide') : $('.company_field_info')
            .addClass('hide');
        field == 'proposal' ? $('.proposal_field_info').removeClass('hide') : $('.proposal_field_info')
            .addClass('hide');

        if (field == 'company') {
            $('#only_admin').prop('disabled', true).prop('checked', false);
            $('input[name="required"]').prop('disabled', true).prop('checked', false);
            $('#show_on_table').prop('disabled', true).prop('checked', false);
            $('#show_on_client_portal').prop('disabled', true).prop('checked', true);
        } else if (field == 'items') {
            $('#type option[value="link"]').prop('disabled', true);
            $('#show_on_table').prop('disabled', true).prop('checked', true);
            $('#show_on_pdf').prop('disabled', true).prop('checked', true);
            $('#only_admin').prop('disabled', true).prop('checked', false);
        } else {
            $('#only_admin').prop('disabled', false).prop('checked', false);
            $('input[name="required"]').prop('disabled', false).prop('checked', false);
            $('#show_on_table').prop('disabled', false).prop('checked', false);
            $('#show_on_client_portal').prop('disabled', false).prop('checked', false);
            $('#show_on_pdf').prop('disabled', false).prop('checked', false);
            $('#type option[value="link"]').prop('disabled', false);
        }
        $('#type').selectpicker('refresh');
    });

    $('select[name="type"]').on('change', function() {
        var type = $(this).val();
        var options_wrapper = $('#options_wrapper');
        var display_inline = $('.display-inline-checkbox')
        var default_value = $('#default-value-field');

        $('textarea.default-value, input.default-value').val('');

        if (type !== 'link' && type !== 'textarea') {
            $('textarea.default-value').removeAttr('name');
            $('input.default-value').attr('name', 'default_value');
            $('.default-value-textarea-input').addClass('hide');
            $('.default-value-text-input').removeClass('hide');
        }

        if (type == 'select' || type == 'checkbox' || type == 'multiselect') {
            options_wrapper.removeClass('hide');
            if (type == 'checkbox') {
                display_inline.removeClass('hide');
            } else {
                display_inline.addClass('hide');
                display_inline.find('input').prop('checked', false);
            }
        } else if (type === 'link') {
            default_value.addClass('hide');
        } else if (type === 'textarea') {
            $('textarea.default-value').attr('name', 'default_value');
            $('input.default-value').removeAttr('name');
            $('.default-value-textarea-input').removeClass('hide');
            $('.default-value-text-input').addClass('hide');
        } else {
            options_wrapper.addClass('hide');
            display_inline.addClass('hide');
            default_value.removeClass('hide')
            display_inline.find('input').prop('checked', false);
        }

        validateDefaultValueField();
    });

    $('body').on('change', 'input[name="only_admin"]', function() {
        $('#show_on_client_portal').prop('disabled', $(this).prop('checked')).prop('checked', false);
        $('#disalow_client_to_edit').prop('disabled', $(this).prop('checked')).prop('checked', false);
    });

    $('body').on('blur', '[name="default_value"], #options', function() {
        validateDefaultValueField();
    });
});

function validateDefaultValueField() {

    var value = $('[name="default_value"]').val();
    var type = $('#type').val();

    var message = '';
    var valid = jQuery.Deferred();
    var $error = $('#default-value-error');
    var $label = $('label[for="default_value"]');
    $label.find('.sample').remove();

    if (type == '') {
        $error.addClass('hide');
        return;
    }

    if (value) {
        value = value.trim();
    }

    switch (type) {
        case 'input':
        case 'link':
        case 'textarea':
            valid.resolve({
                valid: true,
            });
            break;
        case 'number':
            valid.resolve({
                valid: value === '' ? true : new RegExp(/^-?(?:\d+|\d*\.\d+)$/).test(value),
                message: 'Enter a valid number.',
            });
            break;
        case 'multiselect':
        case 'checkbox':
        case 'select':
            if (value === '') {
                valid.resolve({
                    valid: true,
                });
            } else {
                var defaultOptions = value.split(',')
                    .map(function(option) {
                        return option.trim();
                    }).filter(function(option) {
                        return option !== ''
                    });

                if (type === 'select' && defaultOptions.length > 1) {
                    valid.resolve({
                        valid: true,
                        message: 'You cannot have multiple options selected on "Select" field type.',
                    });
                } else {
                    var availableOptions = $('#options').val().split(',')
                        .map(function(option) {
                            return option.trim();
                        }).filter(function(option) {
                            return option !== ''
                        });

                    var nonExistentOptions = defaultOptions.filter(function(i) {
                        return availableOptions.indexOf(i) < 0;
                    });

                    valid.resolve({
                        valid: nonExistentOptions.length === 0,
                        message: nonExistentOptions.join(',') +
                            ' options are not available in the options field.',
                    });
                }
            }

            break;
        case 'date_picker':
        case 'date_picker_time':

            if (value !== '') {
                $.post(admin_url + 'custom_fields/validate_default_date', {
                    date: value,
                    type: type,
                }, function(data) {
                    valid.resolve({
                        valid: data.valid,
                        message: 'Enter date in ' + (type === 'date_picker' ? 'Y-m-d' : 'Y-m-d H:i') +
                            ' format or English date format for the PHP "<a href=\'https://www.php.net/manual/en/function.strtotime.php\'" target="_blank">strtotime</a> function.',
                    });

                    if (data.valid) {
                        $label.append(' <small class="sample">Sample: ' + data.sample + '</small>');
                    }
                }, 'json');
            } else {
                valid.resolve({
                    valid: true,
                });
            }

            break;
        case 'colorpicker':
            valid.resolve({
                valid: value === '' ? true : new RegExp(/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/gm).test(value),
                message: 'Enter color in HEX format, for example: #f2dede',
            })
            break;
    }

    valid.done(function(validation) {
        $('#submitForm').prop('disabled', !validation.valid);
        validation.valid ? $error.addClass('hide') : $error.removeClass('hide');
        $error.html(validation.message);
    });

    return valid;
}
</script>
</body>

</html>