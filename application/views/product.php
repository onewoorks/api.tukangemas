<?php echo $header; ?>
<?php
$totalSankyu = 0;
$totalTEOnline = 0;
$totalEqual = 0;
$totalNew = 0;
$totalSold = 0;
$products = array();
?>
<h3>Stock Migration : Sankyu <-> TukangEmas</h3>
<hr>
<table id='listCategory' class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th colspan="3"></th>
            <th class="text-center" style='width: 10%'>Sankyu</th>
            <th class="text-center" style='width: 10%'>TE Online</th>
            <th class='text-center' colspan="3">Information</th>

        </tr>
        <tr>
            <th>Category</th>
            <th>Sub Category</th>
            <th class="text-center">No Dulang</th>
            <th class='text-center'>Total</th>
            <th class='text-center'>Total</th>
            <th class='text-center'>Equal</th>
            <th class='text-center'>New</th>
            <th class='text-center'>Sold</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($result)):?>
        <?php foreach ($result as $k => $info): ?>
            <?php foreach ($info as $subCat => $dulang): ?>
                <tr data-subcategory='<?= str_replace(' ', '-', $subCat); ?>'>
                    <td><?= strtoupper($k); ?></td>
                    <td><?= strtoupper($subCat); ?></td>
                    <td class="text-center"><?= is_array($dulang['sankyu']) ? implode(',', $dulang['sankyu']) : $dulang['sankyu']; ?></td>
                    <?php $productInfo = Product_Controller::DulangEqual($dulang['sankyu'], $dulang['tukangemas']); ?>
                    <td class='text-center'><?=
                        Product_Controller::DulangSankyu($dulang['sankyu']);
                        $totalSankyu += Product_Controller::DulangSankyu($dulang['sankyu']);
                        ?></td>

                    <td class='text-center'><?=
                        Product_Controller::DulangTukangEmas($dulang['tukangemas']);
                        $totalTEOnline += Product_Controller::DulangTukangEmas($dulang['tukangemas']);
                        ?></td>
                    <td class='text-center'><?=
                $productInfo['equal'];
                $totalEqual += $productInfo['equal'];
                        ?></td>
                    <td class='text-center newLabel'><a href='./new-product/<?= str_replace(' ', '-', $subCat); ?>'><?=
                            $productInfo['new'];
                            $totalNew += $productInfo['new'];
                            ?></a></td>
                    <td class='text-center soldLabel'><a href='./sold-product/<?= str_replace(' ', '-', $subCat); ?>'><?=
                $productInfo['sold'];
                $totalSold += $productInfo['sold'];
                ?></a></td>
                </tr>
    <?php endforeach;
    ?>
<?php endforeach; ?>
                    <?php else:?>
                <tr><td colspan="8"><i>No Products()</i></td></tr>
                <?php endif;?>
    </tbody>
    <tfoot>
    <th colspan="3" class='text-right'>Sum</th>
    <th class='text-center'><?= number_format($totalSankyu, 0, '', ','); ?></th>
    <th class='text-center'><?= number_format($totalTEOnline, 0, '', ','); ?></th>
    <th class='text-center'><?= number_format($totalEqual, 0, '', ','); ?></th>
    <th class='text-center'><?= number_format($totalNew, 0, '', ','); ?></th>
    <th class='text-center'><?= number_format($totalSold, 0, '', ','); ?></th>
</tfoot>
</table>


<div class='text-right'>
    <div id='updateupah' class='btn btn-default'>Synchronize Latest Labour</div>
    <div id='synchronize' class='btn btn-primary'>Synchronize Product</div>
</div>

<script>
    function InsertProduct(v) {
        $.ajax({
            url: 'http://localhost/api.tukangemas/product/new-product/' + $(v).data('subcategory') + '/export-clean',
            success: function () {
                $(v).find('td.newLabel').html(' <i class="glyphicon glyphicon-ok-circle" style="color:green"></i>');
            }
        });
    }

    function UpdateSold(v) {
        $.ajax({
            url: 'http://localhost/api.tukangemas/product/sold-product/' + $(v).data('subcategory') + '/export-clean',
            success: function () {
                $(v).find('td.soldLabel').html(' <i class="glyphicon glyphicon-ok-circle" style="color:green"></i>');
            }
        });
    }

    $(function () {
        $('#synchronize').click(function () {
            $(this).html('processing...');
            var row = $('#listCategory tbody tr');
            $.each(row, function (i, v) {
                $.when(InsertProduct(v)).then(UpdateSold(v));
            });

            $(row).promise().done(function () {
                $('#synchronize').text('Please wait until all columns are indicated with green icon');
                $('#synchronize').removeClass('btn-primary');
                $('#synchronize').addClass('btn-default disabled');
            });
        });
        
        $('#updateupah').click(function(){
           $(this).text('Processing.. Please wait!');
           $.ajax({
               url: 'http://localhost/api.tukangemas/product/update-modal-upah',
               success: function(data){
                  
                   $('#updateupah').text('Completed');
                    alert('update completed! please check!');
               }
           }) 
        });
    });
</script>
<?php echo $footer; ?>