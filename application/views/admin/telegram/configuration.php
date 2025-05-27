<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card-wa-configuration{
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

}
.depField{
    display:none;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-wa-configuration">
                <div class="table-responsive">  
                    <a  data-toggle="modal" onclick="addNumber()" class="btn btn-sm btn-success" data-target="#addEditNewPhone">Add New +</a>
                    <div class="card-body ">
                        <!-- All Configuration Table -->
                        <table class="table table-clients number-index-2 dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th class="toggleable">#</th>
                                <th class="toggleable">Telegram Name</th>
                                <th class="toggleable">Telegram Username</th>
                                <th class="toggleable">Telegram Token</th>
                                <th class="toggleable">Department / Staff</th>
                                <th class="toggleable">Webhook</th>
                                <th class="toggleable">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                            <?php if(!empty($configurationData)){ ?>
                                <?php foreach($configurationData as $CD){ ?>
                                    <tr>
                                        <td class="toggleable"><?= $CD['id'] ?></td>
                                        <td class="toggleable"><?= $CD['telegram_name'] ?></td>
                                        <td class="toggleable"><?= $CD['telegram_username'] ?></td>
                                        <td class="toggleable"><?= $CD['telegram_token'] ?></td>
                                        <td class="toggleable">
                                            <?php if($CD['department_id'] != 0){ ?>
                                                <?= $CD['department_id'] ?>
                                            <?php }else{ ?>
                                                <?= $CD['staff_ids'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="toggleable"><a href="<?= $CD['webhook'] ?>">Webhook</a></td>
                                        <td class="toggleable">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="editNumber(<?= $CD['id'] ?>)">Edit</a>
                                            <a href="<?= admin_url('telegram/delete_configuration/'.$CD['id']) ?>" class="btn btn-danger btn-xs _delete">Delete</a>      
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php }else{ ?>
                                <tr>
                                    <td colspan="7" class="text-center">No Configuration Found</td>
                                </tr>
                            <?php } ?>  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- addNewPhone Model -->
 <!-- Modal -->
 <div class="modal fade" id="addEditNewPhone"  role="dialog" aria-labelledby="addNewPhone" data-backdrop="static">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <form id="configurationForm" action="<?= admin_url('telegram/add_configuration') ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label>Telegram Name</label>
                    <input name="telegram_name" id="name" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Telegram Username</label>
                    <input name="telegram_username" id="username" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Telegram Token</label>
                    <input name="telegram_token" id="token" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="type" class="form-control" onchange="typeRender(this)" >
                        <option value="0">Select any one option...</option>
                        <option value="1">Department</option>
                        <option value="2">Staff</option>
                    </select>
                </div>  
                <div class="form-group depField">
                    <label>Department</label>
                    <select name="department_id" id="department" class="form-control">
                        <option value="0">Select a department</option>
                        <?php foreach($departmentData as $DD){ ?>
                            <option value="<?= $DD['departmentid'] ?>"><?= $DD['name'] ?></option>
                        <?php } ?>
                    </select>
                </div> 
            </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
 <!-- End addNewPhone Model -->
<?php init_tail(); ?>
</body>
<script>
    function typeRender(type){
        console.log(type.value);
        if(type.value == 1){
            $('.depField').show();
            $('#department').attr('required', true);
        }else if(type.value == 2){
            $('.depField').hide();
            $('#department').removeAttr('required');
        }else{
            $('.depField').hide();
            $('#department').removeAttr('required');
        }
    }
</script>

</html>