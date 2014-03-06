<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class="note note-warning">
            <h4 class="block">抽奖说明</h4>
            <div class="row">
                <div class="col-md-6">
                    <table width="50%" class="table">
                        <tr>
                            <th>奖品</th>
                            <th>中奖概率</th>
                        </tr>
                        <tr>
                            <td>平板电脑</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>数码相机</td>
                            <td>9</td>
                        </tr>
                        <tr>
                            <td>音箱设备</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>下次没准就能中哦</td>
                            <td>50</td>
                        </tr>
                    </table>
                </div>
            </div>
           <p>注意<span class="label-info label ">中奖概率</span>必须为整数，你可以将对应的奖项的<span class="label-info label ">中奖概率</span>设置成0，即意味着该奖项抽中的几率是0，<span class="label-info label ">中奖概率</span>的总和（基数），基数越大越能体现概率的准确性。</p>
           <p>上面的例子中<span class="label-info label ">中奖概率</span>的总和为100，那么平板电脑对应的中奖概率就是1%，如果<span class="label-info label ">中奖概率</span>的总和是10000，那中奖概率就是<span class="label-primary label ">万分之一</span>了。</p>
         </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>奖品列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <a onclick="addCoupon()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加奖品</a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>奖品名称</th>
                            <!-- <th>奖品数量</th> -->
                            <th>中奖概率</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['name']?></td>
                            <!-- <td><?=$row['nums']?></td> -->
                            <td><?=$row['probability']?></td>
                            <td>
                                <a  href="#myModal" data-toggle="modal"  onclick="editCoupon(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <button class="btn btn-danger" onclick='delCoupon(<?=$row['id']?>, false)'><i class="icon-remove icon-white"></i> 删除</button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function addCoupon(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加奖品')
}
function editCoupon(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function delCoupon(id, code){
    common_del('<?=site_url($controller_url."del")?>', id, code, "#lottery_view");
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
