<template>
	<div>
         <draggable :list="value.data" element="div" :options="{group: {name: value.type+'-'+index, put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: disableDrag}">
            <div class="row" v-for="(opdata, opindex) in value.data">
                <link-item :not-show-options="notShowOptions" :value="opdata" @removeItem="removeItem(value.data, opindex)" :index="opindex"></link-item>
            </div>
        </draggable>
        <div class="text-xs-center" v-if="!notShowOptions">
            <button class="btn btn-primary btn-sm" @click="addLinkItem(value)">Add New <span v-text="value.bulletName ? value.bulletName : 'Link'" ></span></button>
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
            value: {
                type: Object,
                required: false,
            },
            index: {
                required: true,
                default: 0,
            },
        },

        data() {
            return {
                disableDrag: false,
            };
        },

        computed: {
            disableDrag() {
                return this.value.data.filter(exp => exp.editMode).length > 0
                    || this.notShowOptions;
            },
        },

        watch: {
            disableDrag(newVal) {
                this.value.disableDrag = newVal;
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editName', false);
            this.setValueIfUndefined('disableDrag', false);
        },

        methods: {
            removeItem: function (secdata, index) {
                secdata.splice(index, 1);
            },
            
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            addLinkItem: function (value) {
                var toPush;
                toPush = {
                    editMode: true,
                    link: '',
                    title: '',
                    description: '',
                    saved: false,
                };
                value.data.push(toPush);
            },
        },
    };
</script>