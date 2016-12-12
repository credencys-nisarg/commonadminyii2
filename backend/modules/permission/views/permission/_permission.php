
<?php if (!empty($classMethods)) { ?>
    <div class="form-group">
        <label for="permission-actions" class="control-label">Access Permission</label>
        <div class = "panel-group" id = "accordion">
            <?php
            $i = 1;
            foreach ($classMethods as $key => $val) {
                // Check user already added permission
                $permission = (isset($userPermission[$key])) ? $userPermission[$key] : [];
                $inClass = '';
                if($i == 1){
                    $inClass = 'in';
                }
                ?>
                <div class = "panel panel-default">

                    <div class = "panel-heading">
                        <h4 class = "panel-title">
                            <a data-toggle = "collapse" data-parent = "#accordion" href = "#collapse<?php echo $i; ?>">
                                <?php echo ucfirst($key); ?>
                            </a>
                        </h4>
                    </div>

                    <div id = "collapse<?php echo $i; ?>" class = "panel-collapse collapse <?php echo $inClass;?>">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="checkbox">
                                            <?php 
                                            $checked = '';
                                            if(count($permission) == count($val)){
                                                $checked = "checked";
                                            }
                                            ?>
                                            <label>
                                                <input type="checkbox" class="all-chk" id="chkgrp<?php echo $i; ?>" <?php echo $checked;?>>All
                                            </label>
                                        </div>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                foreach ($val as $skey => $sval) {
                                    $checked = '';
                                    if(in_array($sval, $permission)){
                                        $checked = "checked";
                                    }
                                    if ($j % 4 == 0) {
                                        ?>
                                        <tr>
                                        <?php } ?>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="sub-chk sub_chkgrp<?php echo $i; ?>" value="<?php echo $sval; ?>" name="action[<?php echo $key; ?>][]" <?php echo $checked;?>><?php echo ucfirst($sval); ?>
                                                </label>
                                            </div>
                                        </td>

                                        <?php if ($j % 4 == 3) { ?>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                    $j++;
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                </div>
                <?php
                $i++;
            }
            ?>
        </div>
    </div>
    <?php
}?>
