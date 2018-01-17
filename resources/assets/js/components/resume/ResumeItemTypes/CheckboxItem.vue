<template>
    <div>
        <fieldset class="form-group">
            <div v-if="notShowOptions">
                <input :name="itemType+'-'+parentIndex" type="checkbox" :id="itemType+'-'+parentIndex+'-item-'+index" v-model="choiceData.selected">
                <label :for="itemType+'-'+parentIndex+'-item-'+index" v-text="choiceData.title"></label>
            </div>
            <div v-if="!notShowOptions">
                <div v-if="!choiceData.editing">
                    <p class="with-pencil inline"  :class="notShowOptions || !editing ? 'no-pencil' : ''">
                        <input :name="itemType+'-'+parentIndex" type="checkbox" :id="itemType+'-'+parentIndex+'-item-'+index" :checked="choiceData.selected" class="with-gap" checked="" disabled="">
                        <label class="checked" :for="itemType+'-'+parentIndex+'-item-'+index" v-text="choiceData.title" @click="editItem()"></label>
                    </p>
                    <small class="small-edit tag tag-danger pointed" @click="removeItem()"><i class="fa fa-remove"></i></small>
                </div>
                <div v-else>
                    <input :name="itemType+'-'+parentIndex" type="checkbox" :id="itemType+'-'+parentIndex+'-item-'+index" :checked="choiceData.selected">
                    <label class="width-100">
                        <input type="text" class="small label-inp" placeholder="Add a Choice." @keyup.enter="saveItem()" @keyup.esc="cancelItem()" v-model="tempValue.title">
                    </label>
                    <p class="text-xs-right no-marg-btm"><small class="text-muted">Press 'Enter' to Save and 'Esc' to Cancel</small></p>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
    export default {
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            choiceData: {
                type: Object,
                required: true,
            },
            itemType: {
                type: String,
            },
            bulletName: {
                type: String,
                default: 'Title',
            },
            question: {
                type: Boolean,
                default: false,
            },
            index: {
                required: true,
                default: 0,
            },
            parentIndex: {
                required: true,
                default: 0,
            },
            editing: {
                type: Boolean,
                default: false,
            },
            initialValue: {
                type: Array,
                default: [],
            },
        },
        
        beforeMount() {
            this.setIfUndefined(this.choiceData, 'selected', false);
        },

        data() {
            return {
                tempValue: {
                    editing: true,
                    title: '',
                    selected: false,
                    saved: false,
                },
            };
        },

        mounted() {
            if (_.indexOf(this.initialValue, this.choiceData.title) >= 0) {
                this.choiceData.selected = true;
            }
        },

        computed: {
            readOnlyRating() {
                const defaultBehavior = !this.choiceData.editing || this.notShowOptions;
                if (!this.question) {
                    // the rating will be read-only IF NOT EDITING OR IN PREVIEW MODE
                    return defaultBehavior;
                }

                // if it's a question, the rating will be read-only
                // IF NOT IN PREVIEW MODE AND NOT EDITING
                return !this.notShowOptions && !this.choiceData.editing;
            },
        },

        methods: {
            setIfUndefined(object, property, defaultValue) {
                if (object[property] == undefined) {
                    this.$set(object, property, defaultValue);
                }
            },

            removeItem() {
                this.$emit('removeItem');
            },

            emptyTempValue() {
                this.tempValue = {
                    editing: true,
                    title: '',
                    selected: false,
                    saved: false,
                };
            },

            saveItem() {
                this.tempValue.saved = true;
                this.tempValue.editing = false;

                this.choiceData.editing = this.tempValue.editing;
                this.choiceData.title = this.tempValue.title;
                this.choiceData.selected = this.tempValue.selected;
                this.choiceData.saved = this.tempValue.saved;

                this.emptyTempValue();
            },

            cancelItem() {
                if (this.choiceData.saved) {
                    this.choiceData.editing = false;
                }else {
                    this.$emit('removeItem');
                }
            },

            editItem() {
                if (!this.notShowOptions && this.editing) {
                    this.tempValue = Object.assign({}, this.choiceData);
                    this.tempValue.editing = true;
                    this.choiceData.editing = this.tempValue.editing;
                }
            },
        },
    };
</script>