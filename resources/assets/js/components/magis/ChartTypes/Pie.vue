<style scoped>
    .pie-chart {
        width   : 100%;
    }
</style>

<template>
    <div>
        <div :id="chartId" class="pie-chart" :style="'height:' + height + 'px'"></div>   
    </div>
</template>

<script>
    export default {
        props: {
            height: {
                type: String | Number,
                default: 300,
            },
            titleField: {
                type: String,
                default: 'name',
            },
            valueField: {
                type: String,
                default: 'value',
            },
            valueTitle: {
                type: String,
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
            }
        },

        watch: {
            chartData() {
                this.parseChartData();
            },
        },

        data() {
            return {
                parsedChartData: [],
            };
        },

        computed: {
            chartId() {
                return 'pie-' + this.idModifier;
            },
        },

        mounted() {
            this.parseChartData();

            var chart = AmCharts.makeChart(this.chartId, {
                "type": "pie",
                "theme": "none",
                "legend":{
                    "position":"right",
                    "marginRight":100,
                    "autoMargins":false
                },
                "dataProvider": this.parsedChartData,
                "valueField": this.valueField,
                "titleField": this.titleField,
                "outlineAlpha": 0.4,
                "depth3D": 15,
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "angle": 30,
                "export": {
                    "enabled": true
                }
            });
        },

        methods: {
            parseChartData() {
                this.parsedChartData = Array.isArray(this.chartData)
                    ? this.chartData
                    : JSON.parse(this.chartData);
            },
        },
    };
</script>

