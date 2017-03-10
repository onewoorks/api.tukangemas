<?= $header; ?>
<div class="page-header">
    <h3>Formatting Data</h3>
</div>

<div class='row-fluid'>
    <div class='col-sm-12 jsonView' id="jsonview">
        <?= json_encode($result);?>
    </div>
</div>

<script>
    $(function () {
        var jsonString = $('#jsonview').text();
        var jsonPretty = JSON.stringify(JSON.parse(jsonString), null, 4);
        $('#jsonview').text(jsonPretty);
    });
</script>
<?= $footer; ?>