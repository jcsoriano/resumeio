<template>
    <div>
        <div class="col-xs-12 marg-btm-15">
            <draggable :list="choices" element="div" :options="{group: {name: choices+'-'+index, put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: notShowOptions}">
                <div class="row" v-for="(opdata, opindex) in choices">
                    <radio-item :not-show-options="notShowOptions" :choice-data="opdata" @removeItem="removeItem(choices, opindex)" :parent-index="index" :index="opindex" :item-type="itemType" :editing="editing" v-model="answer"></radio-item>
                </div>
            </draggable>
            <div class="row text-xs-center" v-if="!notShowOptions && editing">
                <input type="radio" class="with-gap" disabled checked="">
                <label class="width-100">
                    <input class="small" type="text" @keyup.enter="addNewRadioButton(choices)" v-model="initTitle" placeholder="Add new choice. Press Enter to save.">
                </label>
            </div> 
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            notShowOptions: {
                type: Boolean,
                default: true,
            },
            index: {
                required: true,
            },
            choices: {
                type: Array,
                required: false,
            },
            itemType: {
                type: String,
            },
            editing: {
                type: Boolean,
                default: false,
            },
            value: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                initTitle: '',
                answer: this.value,
            };
        },

        watch: {
            answer(newVal) {
                this.$emit('input', newVal);
            },
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            removeItem: function (secdata, index) {
                secdata.splice(index, 1);
            },

            addNewRadioButton: function (value) {
                if (this.initTitle != '') {
                    var toPush = {
                        editing: false,
                        selected: false,
                        title: this.initTitle,
                        saved: true,
                    };
                    this.$nextTick(function () {
                        value.push(toPush);
                    });
                }
                this.initTitle = '';
            },
        },
    };
</script>