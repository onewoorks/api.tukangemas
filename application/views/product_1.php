<?php echo $header;?>
<?php
$totalSankyu = 0;
$totalTEOnline = 0;
$totalEqual = 0;
$totalNew = 0;
$totalSold = 0;
$products = array();
?>
<table class="table table-bordered table-condensed small">
    <thead>
        <tr>
            <th colspan="2"></th>
            <th class="text-center" style='width: 10%'>Sankyu</th>
            <th class="text-center" style='width: 10%'>TE Online</th>
            <th class='text-center' colspan="3">Information</th>

        </tr>
        <tr>
            <th>Category</th>
            <th>Sub Category</th>
            <th class='text-center'>Total</th>
            <th class='text-center'>Total</th>
            <th class='text-center'>Equal</th>
            <th class='text-center'>New</th>
            <th class='text-center'>Sold</th>
        </tr>
    </thead>
    <tbody>
        
        <?php foreach ($stocks as $k => $info): ?>
            <?php $namaKategori = ''; ?>
    <pre>
        <?php print_r($info);?>
    </pre>
            <?php foreach ($info as $i): ?>
                <?php
                $totalSankyu += $i['sankyu']['jumlah'];
                $totalTEOnline += $i['te_online']['jumlah'];
                $totalEqual += $i['information']['equal'];
                $totalNew += $i['information']['new'];
                $totalSold += $i['information']['sold'];
                ?>
                <tr> 
                    <?php if($namaKategori!=$k):?>
                    <td rowspan="<?php echo count($info);?>"><?php echo (!$namaKategori == $k) ? strtoupper($k) : ''; ?></td>
                    <?php endif;?>
                    <td><?php echo $i['sankyu']['kategori'];?></td>
                    <td class="text-center"><?php echo $i['sankyu']['jumlah']; ?></td>
                    <td class="text-center"><?php echo $i['te_online']['jumlah']; ?></td>
                    <td class="text-center"><?php echo $i['information']['equal']; ?></td>
                    <td class="text-center"><?php echo $i['information']['new']; ?></td>
                    <td class="text-center"><?php echo $i['information']['sold']; ?></td>
                </tr>
                <?php $namaKategori = $k; ?>
                <?php $products[$i['sankyu']['kategori']] = $i['products'];?> 
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    <th colspan="2" class='text-right'>Sum</th>
    <th class='text-center'><?php echo $totalSankyu; ?></th>
    <th class='text-center'><?php echo $totalTEOnline; ?></th>
    <th class='text-center'><?php echo $totalEqual; ?></th>
    <th class='text-center'><?php echo $totalNew; ?></th>
    <th class='text-center'><?php echo $totalSold; ?></th>
</tfoot>
</table>


<div class='text-right'>
    <div id='synchronize' class='btn btn-primary'>Synchronize</div>
</div>

<script>
    $(function(){
        $('#synchronize').click(function(){
            console.log('processing...');
            $.ajax({
                url : 'http://localhost/api.tukangemas/product/sync',
                success : function(data){
                    console.log(data);
                }
            });
        });
    });
</script>
<?php echo $footer;?>