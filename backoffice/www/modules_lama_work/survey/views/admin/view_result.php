<section class="title">
    <!-- We'll use $this->method to switch between survey.create & survey.edit -->
    <h4><?php echo lang('survey:'.$this->method); ?> : <strong><?php echo $survey['description'];?></strong></h4>
</section>

<section class="item">
    <div class="content">

        <div class="form_inputs">
            <div id="chart_container">
            </div>
        </div>

        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
        </div>

    </div>
</section>

<script type="text/javascript">
    $(function () {

        // Radialize the colors
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });

        var surveyResults = <?php echo json_encode($survey_result);?>;
        console.log(surveyResults);

        $.each(surveyResults, function(key, elem){
            var htmlContainer = '<div id="container'+key+'" style="min-width: 30%; height: auto; max-width: 100%; margin: 0px 0px 30px auto"></div>';
            $('#chart_container').append(htmlContainer);

            var chartData = [];
            $.each(elem.summary, function (idx, sumData){
                var pushData = {
                    name: sumData.answer,
                    y: sumData.num_votes,
                    sliced: true,
                    selected: true
                };
                chartData.push(pushData);
            });
            console.log(chartData);

            // Build the chart
            $('#container'+key).highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: elem.question
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            },
                            connectorColor: 'silver'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Persentase Jawaban',
                    data: chartData
                }]
            });
        });

    });
</script>