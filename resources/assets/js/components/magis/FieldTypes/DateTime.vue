<style scoped>
    .error {
        color: red;
    }
    .datetimedropdown {
        padding:0;
    }
    .datetimedropdown-group {
        padding:0;
    }
</style>
<template>
    <div class="row">
        <div class="col-xs-8">
            <input class="form-control" :id="'datepicker-' + name" data-provide="datepicker" :disabled="disabled">
        </div>
        <div class="col-xs-4">
            <input class="form-control" :id="'timepicker-' + name" @change="timeChange($event)" :disabled="disabled">
        </div>
        <input type="hidden" :name="name" :value="value" />
        <!-- <div class="form-group">
            <div class="input-group date" :id="'datetimepicker-' + name">
                <input type="text" class="form-control" :disabled="disabled" :id="name" :value="value"
            @change="input" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar">
                    </span>
                </span>
            </div>
        </div> -->
       <!--  <input 
            type="datetime-local" 
            class="form-control" 
            :id="name" 
            :name="name"
            :placeholder="title" 
            :value="value"
            @input="input"
            :disabled="disabled"
        > -->
        <div v-show="error" class="error" v-for="e in parsedError" v-text="e"></div>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true,
            },
            yearRange: {
                type: Number,
                default: 1,
            },
            value: {
                type: String,
                default: null,
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            type: {
                type: String,
                required: true,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },

        computed: {
            title() {
                return _.startCase(this.name);
            },

            mysqlDateTimeFormat() {
                return this.mysqlDateFormat + ' ' + this.mysqlTimeFormat;
            },
        },

        data() {
            return {
                parsedError: Array.isArray(this.error) ? this.error : JSON.parse(this.error),
                time: '',
                date: '',
                mysqlTimeFormat: 'HH:mm:ss',
                mysqlDateFormat: 'YYYY-MM-DD',
            };
        },

        created() {
        },

        mounted() {
            $('#timepicker-' + this.name).timepicker({
                scrollDefault: 'now',
                timeFormat: 'h:i A',
            }).change((e) => {
                this.time = moment(e.target.value, 'hh:mm A').format(this.mysqlTimeFormat);
            });
            $('#datepicker-' + this.name).on('changeDate', (e) => {
                this.date = moment(e.date).format(this.mysqlDateFormat);
            });
        },

        watch: {
            time() {
                this.parseDate();
            },

            date() {
                this.parseDate();
            },

            value(newVal) {
                var datetimeMoment = moment(newVal, this.mysqlDateTimeFormat);
                var datetimeDate = datetimeMoment.toDate();
                this.time = datetimeMoment.format(this.mysqlTimeFormat);
                this.date = datetimeMoment.format(this.mysqlDateFormat);
                $('#timepicker-' + this.name).timepicker('setTime', datetimeDate);
                $('#datepicker-' + this.name).datepicker('update', datetimeDate);
            },

            error(newVal) {
                this.parsedError = this.error;
            },
        },

        methods: {
            parseDate() {
                var test = this.date + ' ' + this.time;
                if (moment(test, this.mysqlDateTimeFormat).isValid()) {
                    this.$emit('input', test);
                }
            },

            input(event) {
                alert('ahot');
                this.$emit('input', event.target.value);
            },
        },
    };
</script>
