<style scoped>
    .line-chart {
        width   : 100%;
    }
</style>

<template>
    <div>
        <div :id="chartId" class="line-chart" :style="'height:' + height + 'px'"></div>   
    </div>
</template>

<script>
    export default {
        props: {
            height: {
                type: String | Number,
                default: 300,
            },
            dateField: {
                type: String,
                default: 'date',
            },
            multi: {
                type: Boolean,
                default: false,
            },
            valueField: {
                type: String | Array,
                default: 'value',
            },
            valueTitle: {
                type: String | Array,
                default: '',
            },
            chartData: {
                type: String | Array,
                required: true,
            },
            idModifier: {
                type: String,
                default: 'graph',
            },
            exportName: {
                type: String,
            },
            minPeriod: {
                type: String,
                default: 'DD',
            },
        },

        watch: {
            chartData() {
                this.parseProps();
            },

            parsedChartData(newVal) {
                if (this.chart.dataProvider) {
                    this.chart.dataProvider = newVal;
                    this.chart.validateData();
                }
            },
        },

        data() {
            return {
                parsedChartData: [],
                parsedValueField: [],
                parsedValueTitle: [],
                chart: {},
            };
        },

        computed: {
            chartId() {
                return 'line-' + this.idModifier;
            },
        },

        mounted() {
            this.parseProps();

            let graphs = [];
            let legend = {};

            if(this.multi) {
                graphs = this.parsedValueField.map((valField, index) => {
                    return {
                        "balloonText": "[[category]]: [[value]]",
                        "bullet": "round",
                        "hidden": false,
                        "title": this.getValueTitle(index),
                        "valueField": valField,
                        "fillAlphas": 0
                    }
                });

                legend = {
                    "useGraphSettings": true
                };
            } else {
                graphs = [{
                    "id": "g1",
                    "balloon":{
                      "drop":true,
                      "adjustBorderColor":false,
                      "color":"#ffffff"
                    },
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "bulletSize": 5,
                    "hideBulletsCount": 50,
                    "lineThickness": 2,
                    "title": "red line",
                    "useLineColorForBulletBorder": true,
                    "valueField": this.valueField,
                    "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                }];
            }
            

            let chart = AmCharts.makeChart(this.chartId, {
                "type": "serial",
                "theme": "none",
                "marginRight": 40,
                "marginLeft": 40,
                "autoMarginOffset": 20,
                "mouseWheelZoomEnabled":true,
                "legend": legend,
                "dataDateFormat": "YYYY-MM-DD",
                "valueAxes": [{
                    "id": "v1",
                    "axisAlpha": 0,
                    "position": "left",
                    "ignoreAxisWidth":true
                }],
                "balloon": {
                    "borderThickness": 1,
                    "shadowAlpha": 0
                },
                "graphs": graphs,
                "chartScrollbar": {
                    "graph": "g1",
                    "oppositeAxis":false,
                    "offset":30,
                    "scrollbarHeight": this.height / (this.multi ? 10 : 7.5),
                    "backgroundAlpha": 0,
                    "selectedBackgroundAlpha": 0.1,
                    "selectedBackgroundColor": "#888888",
                    "graphFillAlpha": 0,
                    "graphLineAlpha": 0.5,
                    "selectedGraphFillAlpha": 0,
                    "selectedGraphLineAlpha": 1,
                    "autoGridCount":true,
                    "color":"#AAAAAA"
                },
                "chartCursor": {
                    "pan": true,
                    "valueLineEnabled": true,
                    "valueLineBalloonEnabled": true,
                    "cursorAlpha":1,
                    "cursorColor":"#258cbb",
                    "limitToGraph":"g1",
                    "valueLineAlpha":0.2,
                    "valueZoomable":true
                },
                "valueScrollbar":{
                  "oppositeAxis":false,
                  "offset":50,
                  "scrollbarHeight":10
                },
                "categoryField": this.dateField,
                "categoryAxis": {
                    "parseDates": true,
                    "dashLength": 1,
                    "minPeriod": this.minPeriod,
                    "minorGridEnabled": true
                },
                "export": {
                    "enabled": true,
                    "fileName": this.exportName ? this.exportName : this.chartId,
                },
                "dataProvider": this.parsedChartData
            });

            chart.addListener("rendered", zoomChart);

            zoomChart();

            function zoomChart() {
                chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
            }
            
            this.chart = chart;
        },

        methods: {
            parseProps() {
                if (this.chartData) {
                    this.parsedChartData = Array.isArray(this.chartData)
                        ? this.chartData
                        : JSON.parse(this.chartData);
                }
                this.parsedValueField = this.multi
                    ? JSON.parse(this.valueField)
                    : this.valueField;
                if (this.multi) {
                    if (this.valueTitle) {
                        this.parsedValueTitle = Array.isArray(this.valueTitle)
                            ? this.valueTitle
                            : JSON.parse(this.valueTitle);
                    } else {
                        this.parsedValueTitle = [];
                    }
                } else {
                    this.parsedValueTitle = this.valueTitle ? this.valueTitle : _.startCase(this.valueField);
                }
            },

            updateChart(chart) {
                
            },

            getValueTitle(index) {
                return this.parsedValueTitle[index]
                    ? this.parsedValueTitle[index]
                    : _.startCase(this.parsedValueField[index]);
            }
        },
    };
</script>

