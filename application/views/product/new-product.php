<?= $header; ?>
<div class='page-header'>
<h3>List Of New Product : <?= $category_name; ?></h3>
</div>

<ol class='breadcrumb'>
    <li><a href='./'>Home</a></li>
    <li class='active'>New Product</li>
</ol>

<table class='table table-bordered'>
    <thead>
        <tr>
            <th>No</th>
            <th>Product Serial No</th>
            <th class='text-center'>Weight (g)</th>
            <th class='text-center'>Modal Labour (RM)</th>
            <th class='text-center'>Normal Labour (RM)</th>
            <th class='text-center'>Member Labour (RM)</th>
            <th class='text-center'>Dealer Labour (RM)</th>
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
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?= $footer;?>