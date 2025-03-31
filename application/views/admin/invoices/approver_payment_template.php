<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open(admin_url('invoices/approve_payment'), ['id' => 'approve_payment_form', 'enctype' => 'multipart/form-data']); ?>


<?php //print_r($payments);?>
<div class="col-md-12 no-padding animated fadeIn">
    <div class="panel_s">
        <?php echo form_hidden('invoiceid', $invoice->id); ?>
		<?php $date=date("Y-m-d H:i:s");  echo form_hidden('approver_timestamp', $date ); ?>
        <div class="panel-body">
            <h4 class="tw-my-0 tw-font-semibold">
                <?php echo _l('record_payment_for_invoice'); ?> <?php echo e(format_invoice_number($invoice->id)); ?>
            </h4>
            <hr class="hr-panel-separator" />
            <div class="row">
			 
                <div class="col-md-12">
                    
                    <div class="form-gruoup">
					<div class="col-md-6 padding-5"><label for="note" class="control-label">Amount Received : <?php echo $payments[0]['amount'];?></label> </div>
<div class="col-md-6 padding-5"><label for="note" class="control-label"><strong>Payment Date :</strong> <?php echo $payments[0]['daterecorded'];?></label></div>
<div class="col-md-6 padding-5"><label for="note" class="control-label"><strong>Payment ID :</strong> <?php echo $payments[0]['paymentid'];?></label></div>
<div class="col-md-6 padding-5"><label for="note" class="control-label"><strong>Transaction ID :</strong> <?php echo $payments[0]['transactionid'];?></label></div>
<div class="col-md-12 padding-5"><label for="note" class="control-label"><strong>Leave a note :</strong>
<?php echo $payments[0]['note'];?></label> </div>
<div class="col-md-12 padding">
<label for="company_logo" class="control-label"><?php echo _l('Attachement'); ?></label>
<input type="file" name="approver_attachement" class="form-control" value="" data-toggle="tooltip" title="approver_attachement"><br />

<label for="note" class="control-label"><?php echo _l('record_payment_leave_note'); ?></label>
<textarea name="approver_note" class="form-control" rows="8" placeholder="Approver Note" id="approver_note"></textarea>
</div>
                    </div>
                </div>
                
            </div>
        

        <div class="panel-footer text-right">
            <a href="#" class="btn btn-danger"
                onclick="init_invoice(<?php echo e($invoice->id); ?>); return false;"><?php echo _l('cancel'); ?></a>
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"
                data-form="#record_payment_form" class="btn btn-success"><?php echo _l('Approve Now'); ?></button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

