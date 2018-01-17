<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <div class="row">
        <div :id="'timerange-' + name">
            <span class="col-xs-5">
                <input type="text" class="time start form-control " />
            </span>
            <span class="col-xs-2">
                to
            </span>
            <span class="col-xs-5">
                <input type="text" class="time end form-control col-sm-4" />
            </span>
        </div>
        <input type="hidden" :name="name" :value="value" />
        <div v-show="error" class="error" v-for="e in parsedError" v-text="e"></div>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            },
            value: {
                type: String,
                default: null
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                }
            },
            disabled: {
                type: Boolean,
                default: false
            },
        },
        computed: {
            title() {
                return _.startCase(this.name);
            },
        },
        data() {
            return {
                parsedError: Array.isArray(this.error) ? this.error : JSON.parse(this.error),
                start: '',
                end: '',
                mysqlTimeFormat: 'HH:mm:ss',
                jStart: null,
                jEnd: null,
                jDatepair: null
            }
        },
        mounted() {
            // initialize input widgets first
            $('#timerange-' + this.name + ' .time').timepicker({
                showDuration: true,
                scrollDefault: 'now',
                timeFormat: 'h:i A'
            });
            this.jStart = $('#timerange-' + this.name + ' .time.start');
            this.jEnd = $('#timerange-' + this.name + ' .time.end');
            this.jDatepair = $('#timerange-' + this.name);
            var datepair = new Datepair(document.getElementById('timerange-' + this.name));
            this.jDatepair.on('rangeSelected', function () {
                this.start = moment(this.jStart.val(), 'hh:mm A').format(this.mysqlTimeFormat);
                this.end = moment(this.jEnd.val(), 'hh:mm A').format(this.mysqlTimeFormat);
            }.bind(this));
        },
        watch: {
            start() {
                this.input();
            },
            end() {
                this.input();
            },
            value(newVal) {
                var [start, end] = newVal.split(' to ');
                this.start = start;
                this.end = end;
                $('#timepicker-' + this.name + ' .time.start').timepicker('setTime', start);
                $('#timepicker-' + this.name + ' .time.end').timepicker('setTime', end);
            },
            error(newVal) {
                this.parsedError = this.error;
            }
        },
        methods: {
            input() {
                this.$emit('input', this.start + ' to ' + this.end);
            },
        }
    }
</script>
