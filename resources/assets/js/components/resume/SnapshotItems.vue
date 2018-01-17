<style scoped>
    .cursor-move {
        cursor: pointer;
    }
</style>
<template>
    <div>
        <span v-if="!value.editing || notShowOptions" >
            <span @click="editExpItem()" v-if="value.saved"  class="with-pencil" :class="notShowOptions ? 'no-pencil' : 'cursor-move'">
                <span v-text="'['+value.title+']'"></span>
                &nbsp;
                <span v-text="value.description"></span>
            </span>
            &nbsp;
            <span class="tag tag-danger remove-btn" v-if="!notShowOptions" @click="removeExp">
                <i class="fa fa-remove"></i>
            </span>
        </span>
        <span v-if="value.editing && !notShowOptions">
            <input type="text" class="small" v-model="tempValue.title" placeholder="Enter Title">
            <textarea type="text" class="md-textarea small" placeholder="Enter Description" v-model="tempValue.description"></textarea>
            <button class="btn btn-primary btn-sm" @click="saveEdit">
                <i class="fa fa-check left inherit-size"></i>
                Save
            </button>
            <button class="btn btn-secondary btn-sm black-text" @click="cancelEdit">
                <i class="fa fa-check left inherit-size"></i>
                Cancel
            </button>
        </span>        
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
        },

        beforeMount() {
            this.setValueIfUndefined('editing', false);
            this.setValueIfUndefined('saved', true);
        },

        data() {
            return {
                tempValue: {}
            };
        },

        created() {
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
                    title: '',
                    description: '',
                    editing: true,
                    saved: false,
                };
            },

            editExpItem() {
                if (!this.notShowOptions) {
                    this.value.editing = true;
                    this.tempValue = Object.assign({}, this.value);
                    this.tempValue.editing = true;
                }
            },

            cancelEdit(dataType) {
                if (this.value.saved) {
                    this.value.editing = false;
                } else {
                    this.$emit('removeItem');
                }

                this.emptyTempValue();
            },

            saveEdit() {
                if (this.tempValue.title == '' || this.tempValue.description == '') {
                    toastr.warning('Please Answer all Fields');
                } else {
                    this.tempValue.editing = false;
                    this.tempValue.saved = true;

                    // this.value = this.tempValue;

                    this.value.title = this.tempValue.title;
                    this.value.description = this.tempValue.description;
                    this.value.editing = this.tempValue.editing;
                    this.value.saved = this.tempValue.saved;

                    this.tempValue = {
                        title: '',
                        description: '',
                        editing: null,
                        saved: null,
                    };
                }
            },

            removeExp() {
                this.$emit('removeItem');
            },
        },
    };
</script>