<?= $header; ?>
<div class='page-header'>
<h3>List Of New Product : <?= $category_name; ?></h3>
</div>

<ol class='breadcrumb'>
    <li><a href='<?= SITE_ROOT;?>product/check-in'>Home</a></li>
    <li class='active'>New Product</li>
</ol>

<div class='row hidden'>
    <div class='col-sm-12 text-right'>
        <div class='btn btn-primary' id='btnTransfer'>Transfer</div>
    </div>
</div>
<br>

<table class='table table-bordered table-condensed'>
    <thead>
        <tr>
            <th>No</th>
            <th>Product Serial No</th>
            <th class='text-center'>Weight (g)</th>
            <th class='text-center'>Modal Labour (RM)</th>
            <th class='text-center'>Normal Labour (RM)</th>
            <th class='text-center'>Member Labour (RM)</th>
            <th class='text-center'>Dealer Labour (RM)</th>
            <th class='text-center'>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($result as $k=>$np):?>
        <tr>
            <td><?= ($k+1);?></td>
            <td><?= $np['no_siri_Produk'];?></td>
            <td class='text-center'><?= $np['berat'];?></td>
            <td class='text-center'><?= $np['upah_modal'];?></td>
            <td class='text-center'><?= $np['upah_normal'];?></td>
            <td class='text-center'><?= $np['upah_member'];?></td>
            <td class='text-center'><?= $np['upah_dealer'];?></td>
            <td class='text-center'><?= $np['harga'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<script>
    $(function(){
        $('#btnTransfer').on('click',function(){
            var url = window.location.href;
            window.location = url+'/export';
        });
    });
    </script>
<?= $footer;?>