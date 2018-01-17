<template>
    <div>
        <draggable :list="value.data" element="div" :options="{group: {name: value.type+'-'+index, put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: disableDrag}">
            <div class="row" v-for="(opdata, opindex) in value.data">
                <rating-item :bullet-name="value.bulletName" :not-show-options="notShowOptions" :value="opdata" @removeItem="removeItem(value.data, opindex)" :index="opindex" :item-type="value.type" :question="value.question"></rating-item>
            </div>
        </draggable>
        <div class="text-xs-center" v-if="!notShowOptions">
            <button class="btn btn-primary btn-sm" @click="addNewSkill(value)">Add New <span v-text="value.bulletName ? value.bulletName : 'Item'" ></span></button>
        </div> 
    </div>
</template>
<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            notShowOptions: {
                required: true,
                default: true,
            },
            value: {
                type: Object,
                required: false,
            },
            index: {
                required: true,
                default: 0,
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editName', false);
            this.setValueIfUndefined('question', false);
            this.setValueIfUndefined('disableDrag', false);
        },

        data() {
            return {
                disableDrag: false,
            };
        },

        computed: {
            disableDrag() {
                return this.value.data.filter(exp => exp.editing).length > 0
                    || this.notShowOptions;
            },
        },

        watch: {
            disableDrag(newVal) {
                this.value.disableDrag = newVal;
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

            addNewSkill: function (value) {
                var toPush = {
                    editing: true,
                    title: '',
                    rating: 1,
                    saved: false,
                };
                this.$nextTick(function () {
                    value.data.push(toPush);
                });
            },
        },
    };
</script>