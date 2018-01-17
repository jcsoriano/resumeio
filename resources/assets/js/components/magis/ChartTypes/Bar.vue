<style scoped>
    .bar-chart {
        width   : 100%;
    }
</style>

<template>
    <div>
        <div :id="chartId" class="bar-chart" :style="'height:' + height + 'px'"></div>   
    </div>
</template>

<script>
    export default {
        props: {
            height: {
                type: String | Number,
                default: 300,
            },
            multi: {
                type: Boolean,
                default: false,
            },
            categoryField: {
                type: String,
                default: 'name',
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
            }
        },

        watch: {
            chartData() {
                this.parseProps();
            },
            valueField() {
                this.parseProps();
            },
            valueTitle() {
                this.parseProps();
            },
            multi() {
                this.parseProps();
            },
        },

        data() {
            return {
                parsedChartData: [],
                parsedValueTitle: [],
                parsedValueField: [],
                colors: [
                    '#FF0F00',
                    '#FF6600',
                    '#FF9E01',
                    '#FCD202',
                    '#F8FF01',
                    '#B0DE09',
                    '#04D215',
                    '#0D8ECF',
                    '#0D52D1',
                    '#2A0CD0',
                    '#8A0CCF',
                    '#CD0D74',
                    '#754DEB',
                    '#DDDDDD',
                    '#999999',
                    '#333333',
                    '#000000',
                ],
            };
        },

        computed: {
            chartId() {
                return 'bar-' + this.idModifier;
            },
        },

        mounted() {
            this.parseProps();

            let graphs = [];
            let valueAxes = [];
            let legend = {};

            if (this.multi) {
                graphs = this.parsedValueField.map((valField, index) => {
                    return {
                        "balloonText": this.getValueTitle(index) + ":[[value]]",
                        "fillAlphas": 0.8,
                        "id": "AmGraph-" + index,
                        "lineAlpha": 0.2,
                        "title": this.getValueTitle(index),
                        "type": "column",
                        "valueField": valField,
                    };
                });

                valueAxes = [{
                    "position": "left",
                }];

                legend = {
                    "useGraphSettings": true
                };
            } else {
                graphs = [{
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillColorsField": "color",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "valueField": this.parsedValueField
                }];

                valueAxes = [{
                    "position": "left",
                    "title": this.valueTitle,
                }];
            }

            var chart = AmCharts.makeChart(this.chartId, {
                "theme": "none",
                "type": "serial",
                "startDuration": 2,
                "legend": legend,
                "dataProvider": this.parsedChartData,
                "valueAxes": valueAxes,
                "graphs": graphs,
                "depth3D": 20,
                "angle": 30,
                "chartCursor": {
                    "categoryBalloonEnabled": false,
                    "cursorAlpha": 0,
                    "zoomable": false
                },
                "categoryField": this.categoryField,
                "categoryAxis": {
                    "gridPosition": "start",
                    "labelRotation": 90
                },
                "export": {
                    "enabled": true,
                    "fileName": this.exportName ? this.exportName : this.chartId,
                 }

            });
        },

        methods: {
            parseProps() {
                this.parsedChartData = Array.isArray(this.chartData)
                    ? this.chartData
                    : JSON.parse(this.chartData);
                this.parsedValueTitle = this.multi
                    ? JSON.parse(this.valueTitle)
                    : this.valueTitle;
                this.parsedValueField = this.multi
                    ? JSON.parse(this.valueField)
                    : this.valueField;
                if(!this.multi) {
                    this.addColors();
                }
            },

            addColors() {
                this.parsedChartData = this.parsedChartData.map((val, index) => {
                    val.color = this.colors[index];
                    return val;
                });
            },

            getValueTitle(index) {
                return this.parsedValueTitle[index]
                    ? this.parsedValueTitle[index]
                    : _.startCase(this.parsedValueField[index]);
            }
        },
    };
</script>

