<template>
    <div>
        <div v-if="!value.editing || notShowOptions">
            <div class="col-lg-4 date-holder">
                <p>
                    <span v-text="value.question"></span>
                </p>
            </div>
            <div class="description-holder"  :class="notShowOptions ? 'col-lg-8' : 'col-lg-7'">
                <component :is="value.type" v-model="value.answer" :label="value.question" :not-show-options="notShowOptions" :data-value="tempValue" :index="index" :choices="value.choices" input-class="small no-marg-top" :editing="value.editing" select-all-on-focus></component>
            </div>
            <div class="col-lg-1" v-if="!notShowOptions">
                <button class="btn btn-primary smallbtns" @click="edit(value)"><i class="fa fa-pencil-square-o"></i></button>
                <button class="btn btn-danger smallbtns" @click="removeItem()"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div v-if="value.editing && !notShowOptions">
            <div class="col-lg-4">
                <input type="text" class="small no-marg-top" v-model="tempValue.question" placeholder="Enter Question" >
                <select class="form-control" v-model="tempValue.type">
                    <option v-for="qt in questionTypes" :value="qt.component" v-text="qt.name"></option>
                </select>
            </div>
            <div class="col-lg-8 description-holder">
                <component :is="tempValue.type" v-model="tempValue.answer" :label="tempValue.question" :not-show-options="notShowOptions" :choices="tempValue.choices" :index="index" :item-type="tempValue.type" input-class="small no-marg-top" :editing="value.editing"></component>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 text-xs-center">
                <button class="btn btn-primary btn-sm" @click="save()">Save</button>
                <button class="btn btn-secondary btn-sm" @click="cancel()">Cancel</button>
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
                questionTypes: [
                    {
                        name: 'Text',
                        component: 'magis-string',
                    },
                    {
                        name: 'Checkboxes',
                        component: 'bullet-checkboxes-question',
                    },
                    {
                        name: 'Multiple Choice',
                        component: 'bullet-radio-question',
                    },
                ],
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
                    question: '',
                    answer: '',
                    type: 'magis-string',
                    saved: false,
                    choices: [],
                };
            },

            save() {
                this.saveTempValue();
            },

            saveTempValue() {
                this.tempValue.editing = false;
                this.tempValue.saved = true;
                this.value.editing = this.tempValue.editing;
                this.value.type = this.tempValue.type;
                this.value.question = this.tempValue.question;
                this.value.answer = this.tempValue.answer;
                this.value.saved = this.tempValue.saved;
                this.value.choices = this.tempValue.choices;

                // this.value.choices = Object.assign({}, this.tempValue.choices);
            },

            cancel() {
                if (this.value.saved) {
                    this.value.editing = false;
                } else {
                    this.$emit('removeItem');
                }
            },

            edit() {
                this.tempValue = Object.assign({}, this.value);
                this.value.editing = true;
            },

            removeItem() {
                this.$emit('removeItem');
            },

            getPlaceholder() {
                return 'Enter ' + this.bulletName;
            },
        },
    };
</script>