<template>
    <div>
        <fieldset class="form-group">
            <div v-if="notShowOptions && choiceData.saved">
                <input :name="itemType+'-'+parentIndex" type="radio" class="with-gap" :id="itemType+'-'+parentIndex+'-item-'+index" :checked="choiceData.selected" :value="choiceData.title" v-model="selected">
                <label :for="itemType+'-'+parentIndex+'-item-'+index" v-text="choiceData.title"></label>
            </div>
            <div v-if="!notShowOptions">
                <div v-if="!choiceData.editing && !notShowOptions">
                    <p class="with-pencil inline"  :class="notShowOptions || !editing ? 'no-pencil' : ''">
                        <input :name="itemType+'-'+parentIndex" type="radio" :id="radioItemId" :checked="choiceData.selected" class="with-gap" disabled>
                        <label :for="radioItemId" v-text="choiceData.title" @click="editItem()"></label>
                    </p>
                    <small class="small-edit tag tag-danger pointed" @click="removeItem()"><i class="fa fa-remove"></i></small>
                </div>
                <div v-if="choiceData.editing && !notShowOptions">
                    <input :name="itemType+'-'+parentIndex" type="radio" :id="radioItemId"  :checked="choiceData.selected" class="with-gap" disabled>
                    <label class="width-100">
                        <input type="text" class="small label-inp" :id="radioItemId + '-label-input'" placeholder="Add a Choice." @keyup.enter="saveItem()" @keyup.esc="cancelItem()" v-model="tempValue.title">
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
            value: {
                type: String,
                default: '',
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
        },

        beforeMount() {
            this.setValueIfUndefined('editing', false);
            this.setValueIfUndefined('saved', true);
        },

        data() {
            return {
                tempValue: {
                    editing: true,
                    title: '',
                    selected: false,
                    saved: false,
                },
                selected: this.value,
            };
        },

        watch: {
            selected(newVal) {
                this.$emit('input', newVal);
            },
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

            radioItemId() {
                return this.itemType + '-' + this.parentIndex + '-item-' + this.index + '-' + Math.floor(Math.random() * 1000000);
            }
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.choiceData[property] === undefined) {
                    this.$set(this.choiceData, property, defaultValue);
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

                    this.$nextTick(() => {
                        document.getElementById(this.radioItemId + '-label-input').focus();
                    });
                }
            },

        },
    };
</script>