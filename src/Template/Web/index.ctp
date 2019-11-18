<css>
    <?= $this->Html->css('jquery.dataTables.min.css') ?>
    <?= $this->Html->css('custom.css') ?>
</css>
<div class="contain">
    <div class="contained">
        <div id="MyClockDisplay" class="clock" onload="showTime()">00:00:00</div>
        <div class="table100 ver1">
            <table data-vertable="ver2" class="sort-table no-swoole fixed_header">
                <thead>
                <tr class="row100 head">
                    <th class="column100">ID</th>
                    <th class="column100 column2">Title</th>
                </tr>
                </thead>
                <tbody id="tbody">
                <tr class="row100" id="first-data">
                    <td colspan="2" class="column100" style="text-align: center">EMPTY</td>
                </tr>
                </tbody>
            </table>
            <br>
            <button class="btn btn-danger crawl-no-swoole">Crawl Data</button>
        </div>
    </div>

    <div class="contained table2">
        <div id="MyClockDisplay1" class="clock" onload="showTime()">00:00:00</div>
        <div class="table100 ver1">
            <table data-vertable="ver2" class="sort-table-1 swoole fixed_header">
                <thead>
                <tr class="row100 head">
                    <th class="column100">ID</th>
                    <th class="column100 column2">Title</th>
                </tr>
                </thead>
                <tbody id="tbody1">
                <tr class="row100" id="first-data-1">
                    <td colspan="2" class="column100" style="text-align: center">EMPTY</td>
                </tr>
                </tbody>
            </table>
            <br>
            <button class="btn btn-danger crawl-swoole">Crawl Data</button>
        </div>
    </div>
</div>
<?= $this->HTML->script([
    'jquery.dataTables.min.js',
    'custom.js',
]) ?>
<script>
    $(document).ready(function () {
        var interval = null;
        var interval1 = null;

        function showTime(flag = false) {
            var sec = 0;

            function pad(val) {
                return val > 9 ? val : "0" + val;
            }

            interval = setInterval(function () {
                $('#MyClockDisplay').html(pad(parseInt(sec / 3600, 10)) + ':' + pad(parseInt(sec / 60, 10)) + ':' + pad(++sec % 60));
            }, 1000);
        }

        function showTime1(flag = false) {
            var sec = 0;

            function pad(val) {
                return val > 9 ? val : "0" + val;
            }

            interval1 = setInterval(function () {
                $('#MyClockDisplay1').html(pad(parseInt(sec / 3600, 10)) + ':' + pad(parseInt(sec / 60, 10)) + ':' + pad(++sec % 60));
            }, 1000);
        }

        $('.crawl-no-swoole').click(function () {
            $(this).prop("disabled", true);
            $(this).addClass('disabled');

            showTime();
            var url = '<?= $this->Url->build(['controller' => 'Web', 'action' => 'runScript']) ?>';
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                success: function (res) {
                    clearInterval(interval);
                    if (res.error == 0) {
                        $('#first-data').remove();
                        $.each(res.data, function (key, value) {
                            $('.no-swoole tbody').append('<tr class="row100">'
                                + '<td class="column100">' + value['id'] + '</td>'
                                + '<td class="column100">' + value['title'] + '</td>'
                                + '</tr>');
                        });
                        paginator(0);
                    } else {
                        console.log(res);
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            })
        });

        $('.crawl-swoole').click(function () {
            $(this).prop("disabled", true);
            $(this).addClass('disabled');

            showTime1(true);
            var url = '<?= $this->Url->build(['controller' => 'Web', 'action' => 'runScript1']) ?>';
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                success: function (res) {
                    console.log(res)
                    clearInterval(interval1);
                    if (res.error == 0) {
                        $('#first-data-1').remove();
                        $.each(res.data, function (key, value) {
                            $('.swoole tbody').append('<tr class="row100">'
                                + '<td class="column100">' + value['id'] + '</td>'
                                + '<td class="column100">' + value['title'] + '</td>'
                                + '</tr>');
                        });
                        paginator(1);
                    } else {
                        console.log(res);
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            })
        });
    });
</script>
