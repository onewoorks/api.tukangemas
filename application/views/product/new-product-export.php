<?= $header; ?>
<div class="page-header">
    <h3>Formatting Data</h3>
</div>

<div class='row-fluid'>
    <div class='col-sm-12 jsonView' id="jsonview">
        {"patient_monitoring":[{"pm_id_1":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_2":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"gateway"},"status":"online"}},{"pm_id_3":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_4":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_5":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_6":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_7":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}},{"pm_id_8":{"brand":"Welch Allyn","model":"1500","location":{"location_code":"00","location_name":"Emergency Department","ipv4":"","connection":"cow"},"status":"online"}}],"ultrasound":[]}
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