<template>
	<div>
        <div v-if="value.editMode && !notShowOptions">

            <div class="col-xs-12">
                <input type="text" class="small" v-model="tempValue.title" :placeholder="'Enter Title'">
                <input type="text" class="small" v-model="tempValue.link" :placeholder="'Enter Link'">
                <textarea type="text" class="md-textarea np" v-if="!notShowOptions" placeholder="Enter Something" v-model="tempValue.description"></textarea>
                <div class="md-form text-xs-center">
                    <button class="btn btn-primary btn-sm" @click="saveLink()">Save</button>
                    <button class="btn btn-secondary btn-sm" @click="cancelLink()">Cancel</button>
                </div>
            </div>
        </div>
        <div v-if="(!value.editMode || notShowOptions) && value.saved">
            <div :class="notShowOptions ? 'col-lg-12' : 'col-lg-11'">
                <div class="row">
                    <div class="col-xs-12">
                        <h5 style="margin-bottom: 0px;">
                            <a :href="value.link" v-text="value.title" target="_blank"></a>
                        </h5>
                    </div>
                    <div class="col-xs-12">
                        <p>
                            <span>&mdash;</span>
                            &nbsp;
                            <span v-text="value.description"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1" v-if="!notShowOptions">
                <button class="btn btn-primary smallbtns" @click="editLink(value)"><i class="fa fa-pencil-square-o"></i></button>
                <button class="btn btn-danger smallbtns" @click="removeItem()"><i class="fa fa-remove"></i></button>
            </div>
        </div>
	</div>
</template>


<script>
    export default {
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
            bulletName: {
                type: String,
                default: 'Title',
            },
        },

        mounted() {
            // initPhotoSwipeFromDOM(".mdb-lightbox");
        },

        data() {
            return {
                tempValue: {
                    editMode: true,
                    link: '',
                    title: '',
                    description: '',
                    saved: false,
                },
            };
        },

        beforeMount() {
            this.setValueIfUndefined('editMode', false);
            this.setValueIfUndefined('saved', true);
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },
            saveLink () {
                if (this.tempValue.link != '' && this.tempValue.title != '' && this.tempValue.description != '') {
                    this.tempValue.editMode = false;
                    this.tempValue.saved = true;
                    this.value.editMode = this.tempValue.editMode;
                    this.value.link = this.tempValue.link;
                    this.value.title = this.tempValue.title;
                    this.value.description = this.tempValue.description;
                    this.value.saved = this.tempValue.saved;
                    this.tempValue = {
                        editMode: true,
                        dateTo: '',
                        dateFrom: '',
                        title: '',
                        description: '',
                        saved: false,
                    };
                } else {
                    toastr.warning('Please Answer all Fields');
                }
            },
            cancelLink() {
                this.tempValue = {
                    editMode: true,
                    dateTo: '',
                    dateFrom: '',
                    title: '',
                    description: '',
                    saved: false,
                };

                if (this.value.saved) {
                    this.value.editMode = false;
                } else {
                    this.$emit('removeItem');
                }
            },

            editLink() {
                this.tempValue = Object.assign({}, this.value);
                this.value.editMode = true;
            },

            removeItem() {
                this.$emit('removeItem');
            },
        },
    };
</script>