<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2>Hasil Survey</h2>
    </div>

    <div class="block-content">
<!--        Hasil Survey : <strong>--><?php //echo $survey['description'];?><!--</strong>-->
        <div id="chart_container">
        </div>
    </div>
</div>

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

        /*$.each(surveyResults, function(key, elem){
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
        });*/

        //BEGIN - Buat Grafik Bar
        var chartData = [];
        $.each(surveyResults.questions, function(key, elem){
            var question = elem.question;
            var totalValue = 0;
            $.each(elem.summary, function (idx, sumData){
                totalValue += sumData.num_votes * sumData.weight;
            });
            var pushData = new Array();
            pushData.push(question);
            pushData.push(totalValue);

            chartData.push(pushData);
        });

        $('#chart_container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: surveyResults.description
            },
//            subtitle: {
//                text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
//            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Nilai'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Nilai: <b>{point.y:.1f}</b>'
            },
            series: [{
                name: 'Population',
                data: chartData,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.1f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
        //END - Buat Grafik Bar

    });
</script>