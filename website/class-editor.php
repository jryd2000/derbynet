<?php @session_start();
require_once('inc/data.inc');
require_once('inc/banner.inc');
require_once('inc/schema_version.inc');
require_once('inc/authorize.inc');
require_permission(SET_UP_PERMISSION);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title><?php echo group_label(); ?> Editor</title>
<?php require('inc/stylesheet.inc'); ?>
<link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.2.css"/>
<link rel="stylesheet" type="text/css" href="css/class-editor.css"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/mobile-init.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.4.min.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="js/modal.js"></script>
<script type="text/javascript" src="js/dashboard-ajax.js"></script>
<script type="text/javascript" src="js/class-editor.js"></script>
<script type="text/javascript">
function use_subgroups() { return <?php echo json_encode(read_raceinfo_boolean('use-subgroups')); ?>; }
function group_label() { return <?php echo json_encode(group_label()); ?>; }
function group_label_lc() { return <?php echo json_encode(group_label_lc()); ?>; }
function subgroup_label() { return <?php echo json_encode(subgroup_label()); ?>; }
function subgroup_label_lc() { return <?php echo json_encode(subgroup_label_lc()); ?>; }
$(function() { show_edit_all_classes_modal(); });
</script>
</head>
<body>
<?php
  // Since the back button is always obscured, the back button setting doesn't
  // really have much effect.
make_banner(group_label().' Editor', 'setup.php'); ?>

<div id="edit_all_classes_modal" class="modal_dialog hidden block_buttons">
  <form>
    <h3>Drag to Re-order <?php echo group_label(); ?>s</h3>

    <div id="groups_container">
      <ul id="groups" data-role="listview" data-split-icon="gear">
      </ul>
    </div>

    <br/>

    <input type="button" value="Add <?php echo group_label(); ?>" data-enhanced="true"
           onclick="show_add_class_modal();" />

    <br/>

    <input type="button" value="Close" data-enhanced="true"
           onclick="close_edit_all_classes_modal();"/>
  </form>
</div>


<div id="add_class_modal" class="modal_dialog hidden block_buttons">
  <h3>Add New <?php echo group_label(); ?></h3>
  <form>
    <input type="hidden" name="action" value="class.add"/>
    <input name="name" type="text"/>

    <input type="submit" data-enhanced="true"/>
    <input type="button" value="Cancel" data-enhanced="true"
           onclick="close_add_class_modal();"/>
  </form>
</div>


<div id="edit_one_class_modal" class="modal_dialog hidden block_buttons">
  <h3>New <?php echo group_label(); ?> Name</h3>
  <form>
    <input id="edit_class_name" name="name" type="text"/>

    <div id="completed_rounds_extension">
      <p><span id="completed_rounds_count"></span> completed round(s) exist for this class.</p>
    </div>

    <div id="edit_ranks_extension" class="hidden">
      <h3>Drag to Re-order <?php echo subgroup_label(); ?>s</h3>
      <div id="ranks_container">
      </div>
      <br/>
      <input type="button" value="Add <?php echo subgroup_label(); ?>" data-enhanced="true"
             onclick="show_add_rank_modal();" />
      <br/>
    </div>

    <input type="submit" data-enhanced="true"/>
    <input type="button" value="Cancel" data-enhanced="true"
           onclick="close_edit_one_class_modal();"/>

    <div id="delete_class_extension">
    <input type="button" value="Delete <?php echo group_label(); ?>"
           class="delete_button" data-enhanced="true"
           onclick="handle_delete_class();"/>
    </div>
  </form>
</div>

<div id="add_rank_modal" class="modal_dialog hidden block_buttons">
  <h3>Add New <?php echo subgroup_label(); ?></h3>
  <form>
    <input type="hidden" name="action" value="rank.add"/>
    <input type="hidden" name="classid"/>
    <input name="name" type="text"/>

    <input type="submit" data-enhanced="true"/>
    <input type="button" value="Cancel" data-enhanced="true"
           onclick="close_add_rank_modal();"/>
  </form>
</div>

<div id="edit_one_rank_modal" class="modal_dialog hidden block_buttons">
  <h3>New <?php echo subgroup_label(); ?> Name</h3>
  <form>
    <input id="edit_rank_name" name="name" type="text"/>

    <input type="submit" data-enhanced="true"/>
    <input type="button" value="Cancel" data-enhanced="true"
           onclick="close_edit_one_rank_modal();"/>

    <div id="delete_rank_extension">
    <input type="button" value="Delete <?php echo subgroup_label(); ?>"
           class="delete_button" data-enhanced="true"
           onclick="handle_delete_rank();"/>
    </div>
  </form>
</div>

</body>
</html>
