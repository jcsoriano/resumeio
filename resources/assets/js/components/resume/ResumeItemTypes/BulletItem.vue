<template>
    <div>
        <div v-if="(!value.editing || notShowOptions) && value.saved">
            <div class="col-lg-4 col-md-3 date-holder print-date-holder">
                <p>
                    <span v-text="value.dateFrom"></span>
                    <span v-if="withDateRange">
                    &nbsp;-&nbsp;
                    <span v-text="value.dateTo"></span>
                    </span>
                </p>
            </div>
            <div class="description-holder print-description-holder"  :class="notShowOptions ? 'col-lg-8 col-md-9' : 'col-lg-7 col-md-8'">
                <p class="p-important" v-text="value.title"></p>
                <p class="p-description" v-text="value.description"></p>
            </div>
            <div class="col-lg-1 col-md-1" v-if="!notShowOptions">
                <button class="btn btn-primary smallbtns" @click="editWorkEduc(value)"><i class="fa fa-pencil-square-o"></i></button>
                <button class="btn btn-danger smallbtns" @click="removeItem()"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div v-if="value.editing && !notShowOptions">
            <div class="col-lg-4 col-md-3">
                <input type="text" class="small" v-model="tempValue.dateFrom" :placeholder="!withDateRange ? 'Enter Date' : 'Enter Date From'" >
                <input type="text" class="small" v-model="tempValue.dateTo" placeholder="Enter Date To" v-if="withDateRange">
            </div>
            <div class="col-lg-8 col-md-9 description-holder">
                <input type="text" v-if="withDateRange" :placeholder="getPlaceholder()" class="small" v-model="tempValue.title">
                <input type="text" v-if="!withDateRange" :placeholder="getPlaceholder()" class="small" v-model="tempValue.title">
                <textarea type="text" class="md-textarea small" placeholder="Enter Description" v-model="tempValue.description"></textarea>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 text-xs-center">
                <button class="btn btn-primary btn-sm" @click="saveWorkEduc()">Save</button>
                <button class="btn btn-secondary btn-sm" @click="cancelWorkEduc()">Cancel</button>
            </div>
        </div>      
    </div>
</template>

<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            index: {
                type: String | Number,
            },
            notShowOptions: {
                required: true,
                default: false,
            },
            value: {
                type: Object,
                required: true,
            },
            itemType: {
                type: String,
            },
            withDateRange: {
                type: Boolean,
            },
            bulletName: {
                type: String,
                default: 'Title',
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editing', false);
            this.setValueIfUndefined('saved', true);
        },

        data() {
            return {
                tempValue: {},
            };
        },

        mounted() {
            this.emptyTempValue();
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            emptyTempValue() {
                this.tempValue = {
                    editing: true,
                    dateTo: '',
                    dateFrom: '',
                    title: '',
                    description: '',
                    saved: false,
                };
            },

            saveWorkEduc () {
                if (this.tempValue.title != '') {
                    this.saveTempValue();
                } else {
                    toastr.warning('The title field is required');
                }
            },

            saveTempValue() {
                this.tempValue.editing = false;
                this.tempValue.saved = true;
                this.value.editing = this.tempValue.editing;
                this.value.dateTo = this.tempValue.dateTo;
                this.value.dateFrom = this.tempValue.dateFrom;
                this.value.title = this.tempValue.title;
                this.value.description = this.tempValue.description;
                this.value.saved = this.tempValue.saved;
            },

            cancelWorkEduc() {
                if (this.value.saved) {
                    this.value.editing = false;
                } else {
                    this.$emit('removeItem');
                }
            },

            editWorkEduc: function () {
                this.tempValue = Object.assign({}, this.value);
                this.value.editing = true;
            },

            removeItem: function () {
                this.$emit('removeItem');
            },

            getPlaceholder() {
                return 'Enter ' + this.bulletName;
            },
        },
    };
</script>