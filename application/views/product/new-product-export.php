<?= $header; ?>
<div class="page-header">
<h3>Formatting Data <?php echo count($result);?></h3>
</div>

<div class='row-fluid'>
    <div class='col-sm-12 jsonView' id="jsonview">
        <?=  json_encode($result);?>
    </div>
</div>

<?php                                                                  
$data_string = json_encode($result);                                                                                   
                                                                                                                     
$ch = curl_init('http://localhost/api.commerce.tukangemas/public/product/add');                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);   
$result = curl_exec($ch);
?>
                                                                                                                     

<script>
    $(function () {
        var jsonString = $('#jsonview').text();
        var jsonPretty = JSON.stringify(JSON.parse(jsonString), null, 4);
        $('#jsonview').text(jsonPretty);
    });
</script>
<?= $footer; ?>