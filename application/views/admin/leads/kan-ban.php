<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$is_admin = is_admin();
$i        = 0;
foreach ($statuses as $status) {
    $kanBan = new \app\services\leads\LeadsKanban($status['id']);
    $kanBan->search($this->input->get('search'))
    ->sortBy($this->input->get('sort_by'), $this->input->get('sort'));
    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status['id']] ?? null);
    }
    $leads       = $kanBan->get();
    $total_leads = count($leads);
    $total_pages = $kanBan->totalPages();

    $settings = '';
    foreach (get_system_favourite_colors() as $color) {
        $color_selected_class = 'cpicker-small';
        if ($color == $status['color']) {
            $color_selected_class = 'cpicker-big';
        }
        $settings .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
    } ?>
<ul class="kan-ban-col" data-col-status-id="<?php echo e($status['id']); ?>" data-total-pages="<?php echo e($total_pages); ?>"
    data-total="<?php echo e($total_leads); ?>">
    <li class="kan-ban-col-wrapper">
        <div class="border-right panel_s">
            <?php
        $status_color = '';
    if (!empty($status['color'])) {
        $status_color = 'style="background:' . $status['color'] . ';border:1px solid ' . $status['color'] . '"';
    } ?>
            <div class="panel-heading tw-bg-neutral-700 tw-text-white"
                <?php if (isset($status['isdefault']) && $status['isdefault'] == 1) { ?>data-toggle="tooltip"
                data-title="<?php echo _l('leads_converted_to_client') . ' - ' . _l('client'); ?>" <?php } ?>
                <?php echo $status_color; ?> data-status-id="<?php echo e($status['id']); ?>">
                <i class="fa fa-reorder pointer"></i>
                <!-- Kan Ban Card Box Setup Like (Color Order Status Names) -->
                <span class="heading pointer tw-ml-1" <?php if ($is_admin) { ?>
                    data-order="<?php echo e($status['statusorder']); ?>" data-color="<?php echo e($status['color']); ?>"
                    data-name="<?php echo e($status['name']); ?>"
                    onclick="edit_status(this,<?php echo e($status['id']); ?>); return false;"
                    <?php } ?>><?php echo e($status['name']); ?>
                </span> -
                <?php echo app_format_money(
        $summary[$statusSummaryIndex = array_search($status['id'], array_column($summary, 'id'))]['value'],
        $base_currency
    ); ?> - <small>
        <?php 
        $label = $this->session->userdata('leads_page_type') === 'leads' ? "Leads" : "Deals";
                 echo $total_leads . ' ' . $label
        ?>
        </small>
                <a href="#" onclick="return false;" class="pull-right color-white kanban-color-picker kanban-stage-color-picker<?php if (isset($status['isdefault']) && $status['isdefault'] == 1) {
        echo ' kanban-stage-color-picker-last';
    } ?>" data-placement="bottom" data-toggle="popover" data-content="
            <div class='text-center'>
              <button type='button' return false;' class='btn btn-primary btn-block mtop10 new-lead-from-status-close'>
                <?php echo _l('Change Color'); ?>
              </button>
            </div>
            <?php if (is_admin()) {?>
            <hr />
            <div class='kan-ban-settings cpicker-wrapper'>
              <?php echo $settings; ?>
            </div><?php } ?>" data-html="true" data-trigger="focus">
                    <i class="fa fa-angle-down"></i>
                </a>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="status leads-status sortable" data-lead-status-id="<?php echo e($status['id']); ?>">
                        <?php
                foreach ($leads as $lead) {
                    $this->load->view('admin/leads/_kan_ban_card', ['lead' => $lead, 'status' => $status, 'base_currency' => $base_currency]);
                } ?>
                        <?php if ($total_leads > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more"
                            data-load-status="<?php echo e($status['id']); ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1 || $kanBan->getPage() === $total_pages) {
                    echo ' hide';
                } ?>" data-page="<?php echo $kanBan->getPage(); ?>"
                                onclick="kanban_load_more(<?php echo e($status['id']); ?>, this, 'leads/leads_kanban_load_more', 315, 360); return false;"
                                ;>
                                <?php echo _l('load_more'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_leads > 0) {
                    echo ' hide';
                } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php 
                                $notFoundMessage = $this->session->userdata('leads_page_type') === 'leads' ? "Lead not found!" : "Deal not found!";
                                echo $notFoundMessage 
                                ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php $i++;
} ?>
